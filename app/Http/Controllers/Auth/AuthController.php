<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ── POST /api/auth/login ──────────────────────────────────────────────

    public function login(LoginRequest $request): JsonResponse
    {
        $token = auth('api')->attempt($request->only('email', 'password'));

        if (! $token) {
            return response()->json(['message' => 'Email atau password salah.'], 401);
        }

        $user = auth('api')->user();

        if ($user->deleted_at !== null) {
            auth('api')->logout();
            return response()->json(['message' => 'Akun tidak aktif. Hubungi administrator.'], 403);
        }

        return $this->respondWithToken($token);
    }

    // ── GET /api/auth/me ──────────────────────────────────────────────────

   public function me(): JsonResponse
{
    $user     = auth('api')->user();
    $relation = $this->profileRelation();

    if ($relation) {
        $user->load($relation);
    }

    return response()->json(['data' => new UserResource($user)]);
}

    // ── POST /api/auth/refresh ────────────────────────────────────────────

    public function refresh(): JsonResponse
    {
        try {
            $token = auth('api')->refresh();
            return $this->respondWithToken($token);
        } catch (\Exception) {
            return response()->json(['message' => 'Token tidak valid atau sudah kadaluarsa.'], 401);
        }
    }

    // ── POST /api/auth/logout ─────────────────────────────────────────────

    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return response()->json(['message' => 'Berhasil logout.']);
    }

    // ── PUT /api/auth/change-password ─────────────────────────────────────

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        auth('api')->user()->update([
            'password' => Hash::make($request->password),
        ]);

        auth('api')->logout();

        return response()->json(['message' => 'Password berhasil diubah. Silakan login kembali.']);
    }

    // ── Helpers ───────────────────────────────────────────────────────────

   private function respondWithToken(string $token): JsonResponse
{
    $user     = auth('api')->user();
    $relation = $this->profileRelation();

    if ($relation) {
        $user->load($relation);
    }

    return response()->json([
        'access_token' => $token,
        'token_type'   => 'bearer',
        'expires_in'   => auth('api')->factory()->getTTL() * 60,
        'data'         => new UserResource($user),
    ]);
}

    private function profileRelation(): ?string
    {
        $user = auth('api')->user();

        return match (true) {
            $user->hasRole('guru')     => 'teacher',
            $user->hasRole('siswa')    => 'student',
            $user->hasRole('guardian') => 'guardian',
            default                    => null,
        };
    }
}
