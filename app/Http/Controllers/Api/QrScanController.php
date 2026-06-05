<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use App\Services\FonnteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class QrScanController extends Controller
{
    protected FonnteService $fonnte;

    public function __construct(FonnteService $fonnte)
    {
        $this->fonnte = $fonnte;
    }

    public function scan(Request $request): JsonResponse
    {
        $data = $request->validate([
            'qr_token'     => ['required', 'string'],
            'scan_type'    => ['required', 'in:in,out'],
            'verify_photo' => ['nullable', 'string'], // base64 image
        ]);

        $student = Student::with(['classroom', 'classroom.major', 'user', 'guardian'])
            ->where('qr_token', $data['qr_token'])
            ->first();

        if (! $student) {
            return response()->json([
                'success' => false,
                'message' => 'QR tidak dikenali.',
            ], 404);
        }

        $todayShift = $student->getTodayShift();

        if (! $todayShift) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada jadwal shift untuk kelas ini hari ini.',
                'student' => $this->studentSummary($student),
            ], 422);
        }

        $now   = Carbon::now();
        $today = $now->toDateString();

        $shiftStart = Carbon::parse($today . ' ' . $todayShift->start_time);
        $shiftEnd   = Carbon::parse($today . ' ' . $todayShift->end_time);
        $lateLimit  = Carbon::parse($today . ' ' . $todayShift->late_tolerance);

        $scanOpenAt = $shiftStart->copy()->subMinutes(30);

        if ($now->lt($scanOpenAt)) {
            return response()->json([
                'success' => false,
                'message' => sprintf(
                    'Scanner belum dibuka. Shift %s dibuka pukul %s.',
                    $todayShift->name,
                    $scanOpenAt->format('H:i')
                ),
                'student' => $this->studentSummary($student),
            ], 422);
        }

        $attendance = Attendance::where('student_id', $student->id)
            ->whereDate('attendance_date', $today)
            ->first();

        if (! $attendance) {
            $attendance = new Attendance([
                'student_id'      => $student->id,
                'attendance_date' => $today,
            ]);
        }

        if ($data['scan_type'] === 'in') {
            if ($attendance->exists && $attendance->scan_in !== null) {
                return response()->json([
                    'success'    => false,
                    'message'    => 'Siswa sudah scan masuk hari ini.',
                    'student'    => $this->studentSummary($student),
                    'attendance' => $attendance,
                ], 409);
            }

            $isLate = $now->gt($lateLimit);
            $status = $isLate ? 'telat' : 'hadir';
            $notes  = $isLate
                ? sprintf('Terlambat. Scan masuk pukul %s (batas toleransi %s).', $now->format('H:i'), $lateLimit->format('H:i'))
                : null;

            $photoPath = $this->savePhoto($data['verify_photo'] ?? null, $student->nis);

            $attendance->fill([
                'shift_id'   => $todayShift->id,
                'status'     => $status,
                'scan_in'    => $now->format('H:i:s'),
                'updated_by' => null,
                'notes'      => $notes,
                'photo'      => $photoPath,
            ])->save();

            $this->notifyGuardian($student, 'in', $now->format('H:i'), $status);

            return response()->json([
                'success'    => true,
                'is_late'    => $isLate,
                'message'    => $isLate
                    ? sprintf('Terlambat %d menit.', $now->diffInMinutes($lateLimit))
                    : 'Scan masuk berhasil. Selamat belajar!',
                'student'    => $this->studentSummary($student),
                'shift'      => ['name' => $todayShift->name, 'start' => $todayShift->start_time, 'end' => $todayShift->end_time],
                'attendance' => $attendance,
            ]);
        }

        // ── scan_type === 'out' ──────────────────────────────────────────

        if (! $attendance->exists || $attendance->scan_in === null) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa belum scan masuk hari ini.',
                'student' => $this->studentSummary($student),
            ], 422);
        }

        if ($attendance->scan_out !== null) {
            return response()->json([
                'success'    => false,
                'message'    => 'Siswa sudah scan keluar hari ini.',
                'student'    => $this->studentSummary($student),
                'attendance' => $attendance,
            ], 409);
        }

        // Blokir scan keluar SELAMA jam pelajaran (start_time ~ end_time)
        if ($now->between($shiftStart, $shiftEnd)) {
            return response()->json([
                'success' => false,
                'message' => sprintf(
                    'Belum bisa scan keluar. Jam pelajaran masih berlangsung hingga %s.',
                    $shiftEnd->format('H:i')
                ),
                'student'    => $this->studentSummary($student),
                'attendance' => $attendance,
            ], 422);
        }

        $minScanOut = Carbon::parse($today . ' ' . $attendance->scan_in)->addMinutes(30);
        if ($now->lt($minScanOut)) {
            return response()->json([
                'success' => false,
                'message' => sprintf('Scan keluar terlalu cepat. Minimal %s.', $minScanOut->format('H:i')),
                'student' => $this->studentSummary($student),
            ], 422);
        }

        $attendance->update(['scan_out' => $now->format('H:i:s')]);

        $this->notifyGuardian($student, 'out', $now->format('H:i'), $attendance->status);

        return response()->json([
            'success'    => true,
            'message'    => 'Scan keluar berhasil. Sampai jumpa!',
            'student'    => $this->studentSummary($student),
            'shift'      => ['name' => $todayShift->name, 'start' => $todayShift->start_time, 'end' => $todayShift->end_time],
            'attendance' => $attendance->fresh(),
        ]);
    }

    public function studentInfo(Request $request): JsonResponse
    {
        $data = $request->validate([
            'qr_token' => ['required', 'string'],
        ]);

        $student = Student::with(['user', 'classroom.major'])
            ->where('qr_token', $data['qr_token'])
            ->first();

        if (! $student) {
            return response()->json(['success' => false, 'message' => 'QR tidak dikenali.'], 404);
        }

        $todayShift      = $student->getTodayShift();
        $todayAttendance = $student->todayAttendance;

        return response()->json([
            'success' => true,
            'student' => $this->studentSummary($student),
            'shift'   => $todayShift ? [
                'name'           => $todayShift->name,
                'start_time'     => $todayShift->start_time,
                'late_tolerance' => $todayShift->late_tolerance,
                'end_time'       => $todayShift->end_time,
            ] : null,
            'today_attendance' => $todayAttendance ? [
                'scan_in'  => $todayAttendance->scan_in,
                'scan_out' => $todayAttendance->scan_out,
                'status'   => $todayAttendance->status,
            ] : null,
        ]);
    }

    public function regenerateQr(string $studentId): JsonResponse
    {
        $student = Student::findOrFail($studentId);
        $token   = $student->regenerateQrToken();

        return response()->json([
            'message'  => 'QR token berhasil digenerate ulang.',
            'qr_token' => $token,
        ]);
    }

    private function savePhoto(?string $base64, string $nis): ?string
    {
        if (! $base64) return null;

        if (str_contains($base64, ',')) {
            $base64 = explode(',', $base64, 2)[1];
        }

        $decoded = base64_decode($base64, true);
        if ($decoded === false) return null;

        $filename = sprintf('%s_%s.png', $nis, Carbon::now()->format('Ymd_His'));
        $path     = 'attendance-photos/' . $filename;

        Storage::disk('public')->put($path, $decoded);

        return $path;
    }

    private function notifyGuardian(Student $student, string $scanType, string $time, string $status): void
    {
        $guardian = $student->guardian;

        if (! $guardian || ! $guardian->phone_number) {
            return;
        }

        $this->fonnte->sendAttendanceNotification(
            $guardian->phone_number,
            $student->user->name,
            $scanType,
            $time,
            $status
        );
    }

    private function studentSummary(Student $student): array
    {
        return [
            'id'        => $student->id,
            'nis'       => $student->nis,
            'name'      => $student->user->name,
            'photo'     => $student->user->profile_photo_url ?? null,
            'classroom' => $student->classroom
                ? sprintf('%s %s - %s', $student->classroom->grade, $student->classroom->group_number, $student->classroom->major->major_name)
                : null,
        ];
    }
}
