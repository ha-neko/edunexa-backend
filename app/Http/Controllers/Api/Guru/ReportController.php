<?php

namespace App\Http\Controllers\Api\Guru;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // ── GET /api/guru/reports/attendance ──────────────────────────────────

    public function attendance(Request $request): JsonResponse
    {
        $request->validate([
            'classroom_id' => ['nullable', 'ulid'],
            'date_from'    => ['nullable', 'date'],
            'date_to'      => ['nullable', 'date', 'after_or_equal:date_from'],
            'status'       => ['nullable', 'in:hadir,izin,sakit,alpha'],
        ]);

        $teacher      = auth('api')->user()->teacher;
        $classroomIds = $teacher->classrooms()->pluck('id');

        // If a specific classroom is requested, verify ownership
        if ($request->classroom_id && ! $classroomIds->contains($request->classroom_id)) {
            return response()->json(['message' => 'Kelas tidak ditemukan.'], 403);
        }

        $dateFrom = $request->date_from ?? today()->startOfMonth()->toDateString();
        $dateTo   = $request->date_to   ?? today()->toDateString();

        $query = Attendance::with(['student.user', 'student.classroom.major'])
            ->whereHas('student', fn ($q) =>
                $q->whereIn('classroom_id', $request->classroom_id ? [$request->classroom_id] : $classroomIds)
            )
            ->whereBetween('attendance_date', [$dateFrom, $dateTo])
            ->when($request->status, fn ($q) => $q->byStatus($request->status));

        $summary = (clone $query)->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $attendances = $query->orderByDesc('attendance_date')->paginate($request->per_page ?? 30);

        return response()->json([
            'data'    => $attendances,
            'summary' => [
                'hadir' => $summary['hadir'] ?? 0,
                'izin'  => $summary['izin']  ?? 0,
                'sakit' => $summary['sakit'] ?? 0,
                'alpha' => $summary['alpha'] ?? 0,
                'total' => $summary->sum(),
            ],
            'period'  => ['from' => $dateFrom, 'to' => $dateTo],
        ]);
    }
}
