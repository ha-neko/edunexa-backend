<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    // ── GET /api/admin/users ──────────────────────────────────────────────

    public function index(Request $request): JsonResponse
    {
        $users = User::withTrashed()
            ->with(['teacher', 'student', 'guardian'])
            ->when($request->search, fn ($q) =>
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
            )
            ->when($request->role, fn ($q) =>
                $q->whereHas('roles', fn ($r) => $r->where('name', $request->role))
            )
            ->when($request->trashed === 'only', fn ($q) => $q->onlyTrashed())
            ->when($request->trashed !== 'only' && $request->trashed !== 'with', fn ($q) => $q->withoutTrashed())
            ->latest()
            ->paginate($request->per_page ?? 15);

        return response()->json($users);
    }

    // ── GET /api/admin/users/{id} ─────────────────────────────────────────

    public function show(string $id): JsonResponse
    {
        $user = User::withTrashed()
            ->with(['roles', 'permissions', 'teacher', 'student.classroom.major', 'guardian'])
            ->findOrFail($id);

        return response()->json(['data' => $user]);
    }

    // ── POST /api/admin/users ─────────────────────────────────────────────

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', Password::defaults()],
            'role'     => ['required', 'string', 'exists:roles,name'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole($data['role']);

        return response()->json(['message' => 'User berhasil dibuat.', 'data' => $user->load('roles')], 201);
    }

    // ── PUT /api/admin/users/{id} ─────────────────────────────────────────

    public function update(Request $request, string $id): JsonResponse
    {
        $user = User::withTrashed()->findOrFail($id);

        $data = $request->validate([
            'name'     => ['sometimes', 'string', 'max:255'],
            'email'    => ['sometimes', 'email', "unique:users,email,{$user->id}"],
            'password' => ['sometimes', Password::defaults()],
        ]);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return response()->json(['message' => 'User berhasil diperbarui.', 'data' => $user]);
    }

    // ── DELETE /api/admin/users/{id} ──────────────────────────────────────

    public function destroy(string $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User berhasil dihapus.']);
    }

    // ── POST /api/admin/users/{id}/restore ───────────────────────────────

    public function restore(string $id): JsonResponse
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return response()->json(['message' => 'User berhasil dipulihkan.', 'data' => $user]);
    }
}
