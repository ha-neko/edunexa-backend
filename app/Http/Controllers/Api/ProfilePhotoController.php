<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilePhotoController extends Controller
{
    // ── POST /api/user/profile-photo ──────────────────────────────────────
    // Accessible by all authenticated roles (admin, guru, siswa, guardian)

    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user = auth('api')->user();

        // Delete old photo if exists
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $path = $request->file('photo')->store("photos/users", 'public');

        $user->update(['photo' => $path]);

        return response()->json([
            'message'   => 'Foto profil berhasil diperbarui.',
            'photo_url' => Storage::disk('public')->url($path),
        ]);
    }
}
