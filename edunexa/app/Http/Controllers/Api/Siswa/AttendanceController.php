<?php

namespace App\Http\Controllers\Api\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    private function student()
    {
        return auth('api')->user()->student;
    }

    // ── GET /api/siswa/attendances ────────────────────────────────────────

    public function index(Request $request): JsonResponse
    {
        $attendances = $this->student()
            ->attendances()
            ->when($request->status, fn ($q) => $q->byStatus($request->status))
            ->when($request->date_from, fn ($q) =>
                $q->whereDate('attendance_date', '>=', $request->date_from)
            )
            ->when($request->date_to, fn ($q) =>
                $q->whereDate('attendance_date', '<=', $request->date_to)
            )
            ->orderByDesc('attendance_date')
            ->paginate($request->per_page ?? 30);

        // Quick summary
        $summary = $this->student()
            ->attendances()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return response()->json([
            'data'    => $attendances,
            'summary' => [
                'hadir' => $summary['hadir'] ?? 0,
                'izin'  => $summary['izin']  ?? 0,
                'sakit' => $summary['sakit'] ?? 0,
                'alpha' => $summary['alpha'] ?? 0,
            ],
        ]);
    }

    // ── GET /api/siswa/attendances/{attendance} ───────────────────────────

    public function show(string $attendance): JsonResponse
    {
        $attendance = $this->student()
            ->attendances()
            ->findOrFail($attendance);

        return response()->json(['data' => $attendance]);
    }
}
