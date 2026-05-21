<?php

namespace App\Http\Controllers\Api\Guru;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Http\JsonResponse;

class ClassroomController extends Controller
{
    private function teacher()
    {
        return auth('api')->user()->teacher;
    }

    // ── GET /api/guru/classrooms ──────────────────────────────────────────

    public function index(): JsonResponse
    {
        $classrooms = $this->teacher()
            ->classrooms()
            ->with(['major'])
            ->withCount('students')
            ->get();

        return response()->json(['data' => $classrooms]);
    }

    // ── GET /api/guru/classrooms/{classroom} ──────────────────────────────

    public function show(string $classroom): JsonResponse
    {
        $classroom = $this->teacher()
            ->classrooms()
            ->with(['major'])
            ->withCount('students')
            ->findOrFail($classroom);

        return response()->json(['data' => $classroom]);
    }

    // ── GET /api/guru/classrooms/{classroom}/students ─────────────────────

    public function students(string $classroom): JsonResponse
    {
        // Ensure guru only accesses their own classroom
        $classroom = $this->teacher()
            ->classrooms()
            ->findOrFail($classroom);

        $students = $classroom->students()
            ->with(['user', 'todayAttendance'])
            ->get();

        return response()->json([
            'data' => $students,
            'classroom' => $classroom->label,
        ]);
    }
}
