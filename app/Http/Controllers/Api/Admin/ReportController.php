<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Classroom;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // ── GET /api/admin/reports/attendance ─────────────────────────────────

    public function attendance(Request $request): JsonResponse
    {
        $request->validate([
            'classroom_id'  => ['nullable', 'ulid', 'exists:classrooms,id'],
            'date_from'     => ['nullable', 'date'],
            'date_to'       => ['nullable', 'date', 'after_or_equal:date_from'],
            'status'        => ['nullable', 'in:hadir,telat,izin,sakit,alpha'],
        ]);

        $dateFrom = $request->date_from ?? today()->startOfMonth()->toDateString();
        $dateTo   = $request->date_to   ?? today()->toDateString();

        $query = Attendance::with(['student.user', 'student.classroom.major'])
            ->whereBetween('attendance_date', [$dateFrom, $dateTo])
            ->when($request->classroom_id, fn ($q) => $q->byClassroom($request->classroom_id))
            ->when($request->status, fn ($q) => $q->byStatus($request->status));

        // Summary per status
        $summary = (clone $query)->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $attendances = $query->orderByDesc('attendance_date')->paginate($request->per_page ?? 30);

        return response()->json([
            'data' => $attendances,
            'summary' => [
                'hadir' => $summary['hadir'] ?? 0,
                'telat' => $summary['telat'] ?? 0,
                'izin'  => $summary['izin']  ?? 0,
                'sakit' => $summary['sakit'] ?? 0,
                'alpha' => $summary['alpha'] ?? 0,
                'total' => $summary->sum(),
            ],
            'period' => ['from' => $dateFrom, 'to' => $dateTo],
        ]);
    }
}
