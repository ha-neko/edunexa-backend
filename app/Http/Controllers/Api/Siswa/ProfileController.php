<?php

namespace App\Http\Controllers\Api\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    // ── GET /api/siswa/profile ────────────────────────────────────────────

    public function show(): JsonResponse
    {
        $user = auth('api')->user()->load([
            'student.classroom.major',
            'student.guardian.user',
        ]);

        return response()->json(['data' => $user]);
    }
}
