<?php

namespace App\Http\Controllers\Api\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    // ── GET /api/guru/profile ─────────────────────────────────────────────

    public function show(): JsonResponse
    {
        $user = auth('api')->user()->load(['teacher.classrooms.major']);

        return response()->json(['data' => $user]);
    }

    // ── PUT /api/guru/profile ─────────────────────────────────────────────

    public function update(Request $request): JsonResponse
    {
        $user    = auth('api')->user();
        $teacher = $user->teacher;

        $data = $request->validate([
            'name'           => ['sometimes', 'string', 'max:255'],
            'email'          => ['sometimes', 'email', "unique:users,email,{$user->id}"],
            'password'       => ['sometimes', 'current_password:api'],
            'new_password'   => ['sometimes', 'required_with:password', Password::defaults(), 'confirmed'],
            'specialization' => ['sometimes', 'nullable', 'string', 'max:100'],
        ]);

        if (isset($data['name']))           $user->name  = $data['name'];
        if (isset($data['email']))          $user->email = $data['email'];
        if (isset($data['new_password']))   $user->password = Hash::make($data['new_password']);
        $user->save();

        if (isset($data['specialization']) && $teacher) {
            $teacher->update(['specialization' => $data['specialization']]);
        }

        return response()->json([
            'message' => 'Profil berhasil diperbarui.',
            'data'    => $user->fresh(['teacher']),
        ]);
    }
}
