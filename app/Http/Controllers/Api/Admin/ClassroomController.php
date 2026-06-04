<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    // ── GET /api/admin/classrooms ─────────────────────────────────────────

    public function index(Request $request): JsonResponse
    {
        $classrooms = Classroom::withTrashed()
            ->with(['major', 'waliKelas.user'])
            ->withCount('students')
            ->when($request->major_id, fn ($q) => $q->where('major_id', $request->major_id))
            ->when($request->grade, fn ($q) => $q->where('grade', $request->grade))
            ->when($request->academic_year, fn ($q) => $q->where('academic_year', $request->academic_year))
            ->when($request->trashed === 'only', fn ($q) => $q->onlyTrashed())
            ->when($request->trashed !== 'only' && $request->trashed !== 'with', fn ($q) => $q->withoutTrashed())
            ->latest()
            ->paginate($request->per_page ?? 15);

        return response()->json($classrooms);
    }

    // ── GET /api/admin/classrooms/{classroom} ─────────────────────────────

    public function show(string $classroom): JsonResponse
    {
        $classroom = Classroom::withTrashed()
            ->with(['major', 'waliKelas.user', 'students.user'])
            ->withCount('students')
            ->findOrFail($classroom);

        return response()->json(['data' => $classroom]);
    }

    // ── POST /api/admin/classrooms ────────────────────────────────────────

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'major_id'      => ['required', 'ulid', 'exists:majors,id'],
            'grade'         => ['required', 'in:X,XI,XII'],
            'group_number'  => ['required', 'string', 'max:10'],
            'academic_year' => ['required', 'string', 'max:10'],
            'wali_kelas_id' => ['nullable', 'ulid', 'exists:teachers,id'],
        ]);

        $classroom = Classroom::create($data);

        return response()->json([
            'message' => 'Kelas berhasil dibuat.',
            'data'    => $classroom->load(['major', 'waliKelas.user']),
        ], 201);
    }

    // ── PUT /api/admin/classrooms/{classroom} ─────────────────────────────

    public function update(Request $request, string $classroom): JsonResponse
    {
        $classroom = Classroom::withTrashed()->findOrFail($classroom);

        $data = $request->validate([
            'major_id'      => ['sometimes', 'ulid', 'exists:majors,id'],
            'grade'         => ['sometimes', 'in:X,XI,XII'],
            'group_number'  => ['sometimes', 'string', 'max:10'],
            'academic_year' => ['sometimes', 'string', 'max:10'],
            'wali_kelas_id' => ['nullable', 'ulid', 'exists:teachers,id'],
        ]);

        $classroom->update($data);

        return response()->json([
            'message' => 'Kelas berhasil diperbarui.',
            'data'    => $classroom->load(['major', 'waliKelas.user']),
        ]);
    }

    // ── DELETE /api/admin/classrooms/{classroom} ──────────────────────────

    public function destroy(string $classroom): JsonResponse
    {
        $classroom = Classroom::findOrFail($classroom);
        $classroom->delete();

        return response()->json(['message' => 'Kelas berhasil dihapus.']);
    }

    // ── POST /api/admin/classrooms/{classroom}/restore ────────────────────

    public function restore(string $classroom): JsonResponse
    {
        $classroom = Classroom::onlyTrashed()->findOrFail($classroom);
        $classroom->restore();

        return response()->json(['message' => 'Kelas berhasil dipulihkan.', 'data' => $classroom]);
    }
}
