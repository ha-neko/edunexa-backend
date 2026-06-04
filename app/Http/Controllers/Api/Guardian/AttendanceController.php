<?php

namespace App\Http\Controllers\Api\Guardian;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    private function guardian()
    {
        return auth('api')->user()->guardian;
    }

    // ── GET /api/guardian/attendances ─────────────────────────────────────

    public function index(Request $request): JsonResponse
    {
        $studentIds = $this->guardian()->students()->pluck('id');

        $attendances = Attendance::with(['student.user', 'student.classroom.major'])
            ->whereIn('student_id', $studentIds)
            ->when($request->student_id, fn ($q) => $q->where('student_id', $request->student_id))
            ->when($request->status, fn ($q) => $q->byStatus($request->status))
            ->when($request->date_from, fn ($q) =>
                $q->whereDate('attendance_date', '>=', $request->date_from)
            )
            ->when($request->date_to, fn ($q) =>
                $q->whereDate('attendance_date', '<=', $request->date_to)
            )
            ->orderByDesc('attendance_date')
            ->paginate($request->per_page ?? 30);

        return response()->json($attendances);
    }

    // ── GET /api/guardian/attendances/{attendance} ────────────────────────

    public function show(string $attendance): JsonResponse
    {
        $studentIds = $this->guardian()->students()->pluck('id');

        $attendance = Attendance::with(['student.user', 'student.classroom.major'])
            ->whereIn('student_id', $studentIds)
            ->findOrFail($attendance);

        return response()->json(['data' => $attendance]);
    }
}
