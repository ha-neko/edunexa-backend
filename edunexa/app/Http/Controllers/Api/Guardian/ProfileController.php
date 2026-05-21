<?php

namespace App\Http\Controllers\Api\Guardian;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    // ── GET /api/guardian/profile ─────────────────────────────────────────

    public function show(): JsonResponse
    {
        $user = auth('api')->user()->load([
            'guardian.students.user',
            'guardian.students.classroom.major',
        ]);

        return response()->json(['data' => $user]);
    }
}
