<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // ── GET /api/admin/attendances ────────────────────────────────────────

    public function index(Request $request): JsonResponse
    {
        $attendances = Attendance::with(['student.user', 'student.classroom.major', 'updatedBy'])
            ->when($request->classroom_id, fn ($q) => $q->byClassroom($request->classroom_id))
            ->when($request->date, fn ($q) => $q->byDate($request->date))
            ->when($request->status, fn ($q) => $q->byStatus($request->status))
            ->when($request->student_id, fn ($q) => $q->where('student_id', $request->student_id))
            ->orderByDesc('attendance_date')
            ->paginate($request->per_page ?? 15);

        return response()->json($attendances);
    }

    // ── POST /api/admin/attendances — Absensi Manual ──────────────────────

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'student_id'       => ['required', 'ulid', 'exists:students,id'],
            'attendance_date'  => ['required', 'date', 'before_or_equal:today'],
            'status'           => ['required', 'in:hadir,izin,sakit,alpha'],
            'scan_in'          => ['nullable', 'date_format:H:i:s'],
            'scan_out'         => ['nullable', 'date_format:H:i:s'],
            'notes'            => ['nullable', 'string'],
        ]);

        $attendance = Attendance::updateOrCreate(
            [
                'student_id'      => $data['student_id'],
                'attendance_date' => $data['attendance_date'],
            ],
            [
                'status'     => $data['status'],
                'scan_in'    => $data['scan_in'] ?? null,
                'scan_out'   => $data['scan_out'] ?? null,
                'notes'      => $data['notes'] ?? null,
                'updated_by' => auth('api')->id(),
            ]
        );

        return response()->json([
            'message' => 'Absensi berhasil disimpan.',
            'data'    => $attendance->fresh(['student.user']),
        ], 201);
    }

    // ── GET /api/admin/attendances/{attendance} ───────────────────────────

    public function show(string $attendance): JsonResponse
    {
        $attendance = Attendance::with([
            'student.user',
            'student.classroom.major',
            'updatedBy',
        ])->findOrFail($attendance);

        return response()->json(['data' => $attendance]);
    }

    // ── PUT /api/admin/attendances/{attendance} ───────────────────────────

    public function update(Request $request, string $attendance): JsonResponse
    {
        $attendance = Attendance::findOrFail($attendance);

        $data = $request->validate([
            'scan_in'  => ['sometimes', 'nullable', 'date_format:H:i:s'],
            'scan_out' => ['sometimes', 'nullable', 'date_format:H:i:s'],
            'status'   => ['sometimes', 'in:hadir,izin,sakit,alpha'],
            'notes'    => ['sometimes', 'nullable', 'string'],
        ]);

        $attendance->update(array_merge($data, [
            'updated_by' => auth('api')->id(),
        ]));

        return response()->json([
            'message' => 'Absensi berhasil diperbarui.',
            'data'    => $attendance->fresh(['student.user', 'updatedBy']),
        ]);
    }

    // ── DELETE /api/admin/attendances/{attendance} ────────────────────────

    public function destroy(string $attendance): JsonResponse
    {
        $attendance = Attendance::findOrFail($attendance);
        $attendance->delete();

        return response()->json(['message' => 'Absensi berhasil dihapus.']);
    }
}
