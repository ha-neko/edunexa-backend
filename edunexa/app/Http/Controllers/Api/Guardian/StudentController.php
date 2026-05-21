<?php

namespace App\Http\Controllers\Api\Guardian;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class StudentController extends Controller
{
    private function guardian()
    {
        return auth('api')->user()->guardian;
    }

    // ── GET /api/guardian/students ────────────────────────────────────────

    public function index(): JsonResponse
    {
        $students = $this->guardian()
            ->students()
            ->with(['user', 'classroom.major', 'todayAttendance'])
            ->get();

        return response()->json(['data' => $students]);
    }

    // ── GET /api/guardian/students/{student} ──────────────────────────────

    public function show(string $student): JsonResponse
    {
        $student = $this->guardian()
            ->students()
            ->with(['user', 'classroom.major', 'todayAttendance'])
            ->findOrFail($student);

        return response()->json(['data' => $student]);
    }
}
