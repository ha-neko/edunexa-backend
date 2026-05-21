<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guardian;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class GuardianController extends Controller
{
    // ── GET /api/admin/guardians ──────────────────────────────────────────

    public function index(Request $request): JsonResponse
    {
        $guardians = Guardian::withTrashed()
            ->with(['user'])
            ->withCount('students')
            ->when($request->search, fn ($q) =>
                $q->whereHas('user', fn ($u) =>
                    $u->where('name', 'like', "%{$request->search}%")
                      ->orWhere('email', 'like', "%{$request->search}%")
                )->orWhere('phone_number', 'like', "%{$request->search}%")
            )
            ->when($request->trashed === 'only', fn ($q) => $q->onlyTrashed())
            ->when($request->trashed !== 'only' && $request->trashed !== 'with', fn ($q) => $q->withoutTrashed())
            ->latest()
            ->paginate($request->per_page ?? 15);

        return response()->json($guardians);
    }

    // ── GET /api/admin/guardians/{guardian} ───────────────────────────────

    public function show(string $guardian): JsonResponse
    {
        $guardian = Guardian::withTrashed()
            ->with(['user', 'students.user', 'students.classroom.major'])
            ->findOrFail($guardian);

        return response()->json(['data' => $guardian]);
    }

    // ── POST /api/admin/guardians ─────────────────────────────────────────

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'unique:users,email'],
            'password'     => ['required', Password::defaults()],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'address'      => ['nullable', 'string'],
        ]);

        $guardian = DB::transaction(function () use ($data) {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            $user->assignRole('guardian');

            return Guardian::create([
                'user_id'      => $user->id,
                'phone_number' => $data['phone_number'] ?? null,
                'address'      => $data['address'] ?? null,
            ]);
        });

        return response()->json([
            'message' => 'Wali murid berhasil dibuat.',
            'data'    => $guardian->load('user'),
        ], 201);
    }

    // ── PUT /api/admin/guardians/{guardian} ───────────────────────────────

    public function update(Request $request, string $guardian): JsonResponse
    {
        $guardian = Guardian::withTrashed()->with('user')->findOrFail($guardian);

        $data = $request->validate([
            'name'         => ['sometimes', 'string', 'max:255'],
            'email'        => ['sometimes', 'email', "unique:users,email,{$guardian->user_id}"],
            'password'     => ['sometimes', Password::defaults()],
            'phone_number' => ['sometimes', 'nullable', 'string', 'max:20'],
            'address'      => ['sometimes', 'nullable', 'string'],
        ]);

        DB::transaction(function () use ($guardian, $data) {
            $userFields = array_filter([
                'name'     => $data['name'] ?? null,
                'email'    => $data['email'] ?? null,
                'password' => isset($data['password']) ? Hash::make($data['password']) : null,
            ]);

            if ($userFields) {
                $guardian->user->update($userFields);
            }

            $guardian->update(array_intersect_key($data, array_flip(['phone_number', 'address'])));
        });

        return response()->json([
            'message' => 'Wali murid berhasil diperbarui.',
            'data'    => $guardian->fresh('user'),
        ]);
    }

    // ── DELETE /api/admin/guardians/{guardian} ────────────────────────────

    public function destroy(string $guardian): JsonResponse
    {
        $guardian = Guardian::findOrFail($guardian);

        DB::transaction(function () use ($guardian) {
            $guardian->user->delete();
            $guardian->delete();
        });

        return response()->json(['message' => 'Wali murid berhasil dihapus.']);
    }

    // ── POST /api/admin/guardians/{guardian}/restore ──────────────────────

    public function restore(string $guardian): JsonResponse
    {
        $guardian = Guardian::onlyTrashed()->with('user')->findOrFail($guardian);

        DB::transaction(function () use ($guardian) {
            $guardian->user->restore();
            $guardian->restore();
        });

        return response()->json(['message' => 'Wali murid berhasil dipulihkan.', 'data' => $guardian]);
    }
}
