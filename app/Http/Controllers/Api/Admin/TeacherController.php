<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class TeacherController extends Controller
{
    // ── GET /api/admin/teachers ───────────────────────────────────────────

    public function index(Request $request): JsonResponse
    {
        $teachers = Teacher::withTrashed()
            ->with(['user', 'classrooms.major'])
            ->withCount('classrooms')
            ->when($request->search, fn ($q) =>
                $q->whereHas('user', fn ($u) =>
                    $u->where('name', 'like', "%{$request->search}%")
                      ->orWhere('email', 'like', "%{$request->search}%")
                )->orWhere('nip', 'like', "%{$request->search}%")
            )
            ->when($request->trashed === 'only', fn ($q) => $q->onlyTrashed())
            ->when($request->trashed !== 'only' && $request->trashed !== 'with', fn ($q) => $q->withoutTrashed())
            ->latest()
            ->paginate($request->per_page ?? 15);

        return response()->json($teachers);
    }

    // ── GET /api/admin/teachers/{teacher} ─────────────────────────────────

    public function show(string $teacher): JsonResponse
    {
        $teacher = Teacher::withTrashed()
            ->with(['user', 'classrooms.major'])
            ->findOrFail($teacher);

        return response()->json(['data' => $teacher]);
    }

    // ── POST /api/admin/teachers ──────────────────────────────────────────

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'unique:users,email'],
            'password'       => ['required', Password::defaults()],
            'nip'            => ['nullable', 'string', 'max:50', 'unique:teachers,nip'],
            'specialization' => ['nullable', 'string', 'max:100'],
        ]);

        $teacher = DB::transaction(function () use ($data) {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            $user->assignRole('guru');

            return Teacher::create([
                'user_id'        => $user->id,
                'nip'            => $data['nip'] ?? null,
                'specialization' => $data['specialization'] ?? null,
            ]);
        });

        return response()->json([
            'message' => 'Guru berhasil dibuat.',
            'data'    => $teacher->load('user'),
        ], 201);
    }

    // ── PUT /api/admin/teachers/{teacher} ─────────────────────────────────

    public function update(Request $request, string $teacher): JsonResponse
    {
        $teacher = Teacher::withTrashed()->with('user')->findOrFail($teacher);

        $data = $request->validate([
            'name'           => ['sometimes', 'string', 'max:255'],
            'email'          => ['sometimes', 'email', "unique:users,email,{$teacher->user_id}"],
            'password'       => ['sometimes', Password::defaults()],
            'nip'            => ['sometimes', 'nullable', 'string', 'max:50', "unique:teachers,nip,{$teacher->id}"],
            'specialization' => ['sometimes', 'nullable', 'string', 'max:100'],
        ]);

        DB::transaction(function () use ($teacher, $data) {
            $userFields = array_filter([
                'name'     => $data['name'] ?? null,
                'email'    => $data['email'] ?? null,
                'password' => isset($data['password']) ? Hash::make($data['password']) : null,
            ]);

            if ($userFields) {
                $teacher->user->update($userFields);
            }

            $teacher->update(array_filter([
                'nip'            => $data['nip'] ?? null,
                'specialization' => $data['specialization'] ?? null,
            ], fn ($v) => ! is_null($v)));
        });

        return response()->json([
            'message' => 'Guru berhasil diperbarui.',
            'data'    => $teacher->fresh(['user']),
        ]);
    }

    // ── DELETE /api/admin/teachers/{teacher} ──────────────────────────────

    public function destroy(string $teacher): JsonResponse
    {
        $teacher = Teacher::findOrFail($teacher);

        DB::transaction(function () use ($teacher) {
            $teacher->user->delete();
            $teacher->delete();
        });

        return response()->json(['message' => 'Guru berhasil dihapus.']);
    }

    // ── POST /api/admin/teachers/{teacher}/restore ────────────────────────

    public function restore(string $teacher): JsonResponse
    {
        $teacher = Teacher::onlyTrashed()->with('user')->findOrFail($teacher);

        DB::transaction(function () use ($teacher) {
            $teacher->user->restore();
            $teacher->restore();
        });

        return response()->json(['message' => 'Guru berhasil dipulihkan.', 'data' => $teacher]);
    }
}
