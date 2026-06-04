<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Major;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    // ── GET /api/admin/majors ─────────────────────────────────────────────

    public function index(Request $request): JsonResponse
    {
        $majors = Major::withTrashed()
            ->withCount('classrooms')
            ->when($request->search, fn ($q) =>
                $q->where('major_name', 'like', "%{$request->search}%")
                  ->orWhere('major_code', 'like', "%{$request->search}%")
            )
            ->when($request->trashed === 'only', fn ($q) => $q->onlyTrashed())
            ->when($request->trashed !== 'only' && $request->trashed !== 'with', fn ($q) => $q->withoutTrashed())
            ->latest()
            ->paginate($request->per_page ?? 15);

        return response()->json($majors);
    }

    // ── GET /api/admin/majors/{major} ─────────────────────────────────────

    public function show(string $major): JsonResponse
    {
        $major = Major::withTrashed()->withCount('classrooms')->findOrFail($major);

        return response()->json(['data' => $major]);
    }

    // ── POST /api/admin/majors ────────────────────────────────────────────

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'major_code' => ['required', 'string', 'max:10', 'unique:majors,major_code'],
            'major_name' => ['required', 'string', 'max:100'],
        ]);

        $major = Major::create($data);

        return response()->json(['message' => 'Jurusan berhasil dibuat.', 'data' => $major], 201);
    }

    // ── PUT /api/admin/majors/{major} ─────────────────────────────────────

    public function update(Request $request, string $major): JsonResponse
    {
        $major = Major::withTrashed()->findOrFail($major);

        $data = $request->validate([
            'major_code' => ['sometimes', 'string', 'max:10', "unique:majors,major_code,{$major->id}"],
            'major_name' => ['sometimes', 'string', 'max:100'],
        ]);

        $major->update($data);

        return response()->json(['message' => 'Jurusan berhasil diperbarui.', 'data' => $major]);
    }

    // ── DELETE /api/admin/majors/{major} ──────────────────────────────────

    public function destroy(string $major): JsonResponse
    {
        $major = Major::findOrFail($major);
        $major->delete();

        return response()->json(['message' => 'Jurusan berhasil dihapus.']);
    }

    // ── POST /api/admin/majors/{major}/restore ────────────────────────────

    public function restore(string $major): JsonResponse
    {
        $major = Major::onlyTrashed()->findOrFail($major);
        $major->restore();

        return response()->json(['message' => 'Jurusan berhasil dipulihkan.', 'data' => $major]);
    }
}
