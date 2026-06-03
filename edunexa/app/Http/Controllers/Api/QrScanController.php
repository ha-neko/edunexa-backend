<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * QrScanController
 *
 * Endpoint ini adalah inti dari sistem absensi QR.
 * Frontend scanner hanya mengirim token QR — semua validasi
 * (shift, jam, duplikasi, status terlambat) dilakukan di sini.
 *
 * Route: POST /api/attendance/scan  (public — tidak perlu login siswa)
 *        Diamankan via IP whitelist middleware atau secret header dari perangkat scanner.
 */
class QrScanController extends Controller
{
    /**
     * POST /api/attendance/scan
     *
     * Body:
     * {
     *   "qr_token": "abc123...",
     *   "scan_type": "in"   // atau "out"
     * }
     */
    public function scan(Request $request): JsonResponse
    {
        $data = $request->validate([
            'qr_token'  => ['required', 'string'],
            'scan_type' => ['required', 'in:in,out'],
        ]);

        // ── 1. Temukan siswa berdasarkan QR token ─────────────────────
        $student = Student::with(['classroom', 'classroom.major', 'user'])
            ->where('qr_token', $data['qr_token'])
            ->first();

        if (! $student) {
            return response()->json([
                'success' => false,
                'message' => 'QR tidak dikenali.',
            ], 404);
        }

        // ── 2. Cari shift aktif hari ini untuk kelas siswa ────────────
        $todayShift = $student->getTodayShift();

        if (! $todayShift) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada jadwal shift untuk kelas ini hari ini.',
                'student' => $this->studentSummary($student),
            ], 422);
        }

        $now  = Carbon::now();
        $today = $now->toDateString();

        // ── 3. Validasi jam scan ──────────────────────────────────────
        $shiftStart = Carbon::parse($today . ' ' . $todayShift->start_time);
        $shiftEnd   = Carbon::parse($today . ' ' . $todayShift->end_time);
        $lateLimit  = Carbon::parse($today . ' ' . $todayShift->late_tolerance);

        // Boleh scan masuk: 30 menit sebelum jam masuk sampai dengan jam pulang
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

        // ── 4. Cek / buat record absensi hari ini ─────────────────────
        $attendance = Attendance::firstOrNew([
            'student_id'      => $student->id,
            'attendance_date' => $today,
        ]);

        if ($data['scan_type'] === 'in') {
            // Cegah scan masuk ganda
            if ($attendance->exists && $attendance->scan_in !== null) {
                return response()->json([
                    'success'    => false,
                    'message'    => 'Siswa sudah scan masuk hari ini.',
                    'student'    => $this->studentSummary($student),
                    'attendance' => $attendance,
                ], 409);
            }

            $isLate   = $now->gt($lateLimit);
            $status   = $isLate ? 'hadir' : 'hadir'; // tetap hadir, tapi catat terlambat via notes
            $notes    = $isLate
                ? sprintf('Terlambat. Scan masuk pukul %s (batas toleransi %s).', $now->format('H:i'), $lateLimit->format('H:i'))
                : null;

            $attendance->fill([
                'shift_id'   => $todayShift->id,
                'status'     => $status,
                'scan_in'    => $now->format('H:i:s'),
                'updated_by' => null,
                'notes'      => $notes,
            ])->save();

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

        // scan_type === 'out'
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

        // Validasi: tidak boleh scan keluar sebelum setengah jam masuk
        $minScanOut = Carbon::parse($today . ' ' . $attendance->scan_in)->addMinutes(30);
        if ($now->lt($minScanOut)) {
            return response()->json([
                'success' => false,
                'message' => sprintf('Scan keluar terlalu cepat. Minimal %s.', $minScanOut->format('H:i')),
                'student' => $this->studentSummary($student),
            ], 422);
        }

        $attendance->update(['scan_out' => $now->format('H:i:s')]);

        return response()->json([
            'success'    => true,
            'message'    => 'Scan keluar berhasil. Sampai jumpa!',
            'student'    => $this->studentSummary($student),
            'shift'      => ['name' => $todayShift->name, 'start' => $todayShift->start_time, 'end' => $todayShift->end_time],
            'attendance' => $attendance->fresh(),
        ]);
    }

    // ── GET /api/attendance/scan/student-info?qr_token=xxx ────────────────
    // Dipakai frontend untuk preview info siswa setelah QR dibaca,
    // SEBELUM konfirmasi scan. Tidak mencatat absensi.

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

        $todayShift    = $student->getTodayShift();
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

    // ── POST /api/admin/students/{student}/regenerate-qr ─────────────────
    // Hanya admin. Regenerate QR token (misal QR bocor/hilang).

    public function regenerateQr(string $studentId): JsonResponse
    {
        $student = Student::findOrFail($studentId);
        $token   = $student->regenerateQrToken();

        return response()->json([
            'message'   => 'QR token berhasil digenerate ulang.',
            'qr_token'  => $token,
        ]);
    }

    // ── Private helpers ───────────────────────────────────────────────────

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
