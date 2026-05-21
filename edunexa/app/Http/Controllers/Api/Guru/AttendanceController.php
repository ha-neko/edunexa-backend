<?php

namespace App\Http\Controllers\Api\Guru;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    private function teacher()
    {
        return auth('api')->user()->teacher;
    }

    // ── GET /api/guru/attendances ─────────────────────────────────────────

    public function index(Request $request): JsonResponse
    {
        $classroomIds = $this->teacher()->classrooms()->pluck('id');

        $attendances = Attendance::with(['student.user', 'student.classroom', 'updatedBy'])
            ->byClassroom($classroomIds->first()) // scope per classroom; extend if multi-class
            ->when($request->classroom_id, fn ($q) => $q->byClassroom($request->classroom_id))
            ->when($request->date, fn ($q) => $q->byDate($request->date))
            ->when($request->status, fn ($q) => $q->byStatus($request->status))
            ->whereHas('student', fn ($q) => $q->whereIn('classroom_id', $classroomIds))
            ->orderByDesc('attendance_date')
            ->paginate($request->per_page ?? 15);

        return response()->json($attendances);
    }

    // ── POST /api/guru/attendances ────────────────────────────────────────

    public function store(Request $request): JsonResponse
    {
        $teacher      = $this->teacher();
        $classroomIds = $teacher->classrooms()->pluck('id');

        $data = $request->validate([
            'records'                    => ['required', 'array', 'min:1'],
            'records.*.student_id'       => ['required', 'ulid', 'exists:students,id'],
            'records.*.status'           => ['required', 'in:hadir,izin,sakit,alpha'],
            'records.*.scan_in'          => ['nullable', 'date_format:H:i:s'],
            'records.*.scan_out'         => ['nullable', 'date_format:H:i:s'],
            'records.*.notes'            => ['nullable', 'string'],
            'attendance_date'            => ['required', 'date', 'before_or_equal:today'],
        ]);

        // Verify all students belong to guru's classrooms
        $studentIds = collect($data['records'])->pluck('student_id');
        $valid = Student::whereIn('id', $studentIds)
            ->whereIn('classroom_id', $classroomIds)
            ->count();

        if ($valid !== $studentIds->count()) {
            return response()->json(['message' => 'Beberapa siswa tidak termasuk kelas Anda.'], 403);
        }

        $created = collect($data['records'])->map(function ($record) use ($data, $teacher) {
            return Attendance::updateOrCreate(
                [
                    'student_id'      => $record['student_id'],
                    'attendance_date' => $data['attendance_date'],
                ],
                [
                    'status'     => $record['status'],
                    'scan_in'    => $record['scan_in'] ?? null,
                    'scan_out'   => $record['scan_out'] ?? null,
                    'notes'      => $record['notes'] ?? null,
                    'updated_by' => auth('api')->id(),
                ]
            );
        });

        return response()->json([
            'message' => 'Absensi berhasil disimpan.',
            'total'   => $created->count(),
        ], 201);
    }

    // ── PUT /api/guru/attendances/{attendance} ────────────────────────────

    public function update(Request $request, string $attendance): JsonResponse
    {
        $teacher      = $this->teacher();
        $classroomIds = $teacher->classrooms()->pluck('id');

        $attendance = Attendance::whereHas('student', fn ($q) =>
            $q->whereIn('classroom_id', $classroomIds)
        )->findOrFail($attendance);

        $data = $request->validate([
            'status'   => ['sometimes', 'in:hadir,izin,sakit,alpha'],
            'scan_in'  => ['sometimes', 'nullable', 'date_format:H:i:s'],
            'scan_out' => ['sometimes', 'nullable', 'date_format:H:i:s'],
            'notes'    => ['sometimes', 'nullable', 'string'],
        ]);

        $attendance->update(array_merge($data, ['updated_by' => auth('api')->id()]));

        return response()->json([
            'message' => 'Absensi berhasil diperbarui.',
            'data'    => $attendance->fresh(['student.user', 'updatedBy']),
        ]);
    }
}
