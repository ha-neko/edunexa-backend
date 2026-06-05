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

    public function me(): JsonResponse
    {
        $user     = auth('api')->user();
        $relation = $this->profileRelation();

        if ($relation) {
            $user->load($relation);
        }

        return response()->json(['data' => new UserResource($user)]);
    }

    public function refresh(): JsonResponse
    {
        try {
            $token = auth('api')->refresh();
            return $this->respondWithToken($token);
        } catch (\Exception) {
            return response()->json(['message' => 'Token tidak valid atau sudah kadaluarsa.'], 401);
        }
    }

    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return response()->json(['message' => 'Berhasil logout.']);
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user = auth('api')->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Password saat ini tidak sesuai.'], 422);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        auth('api')->logout();

        return response()->json(['message' => 'Password berhasil diubah. Silakan login kembali.']);
    }

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
