<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pin;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    use ApiResponse;

    public function changePin(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_pin' => 'required|string|size:6',
            'pin'         => 'required|string|size:6|confirmed',
        ]);

        $pin = Pin::where('user_id', $user->id)->first();
        if (!$pin) return $this->error('PIN tidak ditemukan.', 404);

        if (!Hash::check($request->current_pin, $pin->pin)) {
            return $this->error('PIN lama yang Anda masukkan salah.', 400, 'INVALID_OLD_PIN');
        }

        $pin->update([
            'pin'             => bcrypt($request->pin),
            'must_change_pin' => false,
            'pin_updated_at'  => now(),
            'pin_attempt'     => 0,
        ]);

        return $this->success([
            'pin_updated_at' => now(),
        ], 'PIN berhasil diperbarui.');
    }
}
