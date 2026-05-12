<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Mail\PinInitialMail;
use App\Mail\PinResendMail;
use App\Models\AuthLog;
use App\Models\Pin;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
            return $this->error('Email atau password yang Anda masukkan salah.', 401);
        }

        $user = auth()->user();

        if (!$user->hasRole('pegawai')) {
            auth()->logout();
            return $this->error('Akses ditolak. Akun Anda tidak memiliki otoritas untuk aplikasi ini.', 403, 'INSUFFICIENT_ROLE');
        }

        if (in_array($user->status, ['Suspended', 'Banned'])) {
            auth()->logout();
            return $this->error('Akun Anda telah ditangguhkan. Silakan hubungi admin.', 403, ['status' => $user->status]);
        }

        if (!$user->email_verified_at) {
            auth()->logout();
            return $this->error('Akun Anda belum terverifikasi.', 403, [
                'next_step' => 'otp_verification',
                'email'     => $user->email,
            ]);
        }

        auth()->logout();

        $pin = Pin::firstOrCreate(['user_id' => $user->id], [
            'must_change_pin' => true,
            'pin_attempt'     => 0,
        ]);

        if ($pin->must_change_pin || !$pin->pin) {
            $newPin = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $pin->update([
                'pin'             => bcrypt($newPin),
                'must_change_pin' => true,
                'pin_updated_at'  => now(),
            ]);
            Mail::to($user->email)->send(new PinInitialMail($newPin, 10));

            AuthLog::create([
                'user_id'    => $user->id,
                'event'      => 'login_pending_pin',
                'ip_address' => $request->ip(),
            ]);

            return $this->success([
                'next_step'       => 'pin_verification',
                'must_change_pin' => true,
                'email'           => $user->email,
            ], 'Login berhasil. Silakan masukkan PIN yang dikirim ke email Anda.');
        }

        AuthLog::create([
            'user_id'    => $user->id,
            'event'      => 'login_pending_pin',
            'ip_address' => $request->ip(),
        ]);

        return $this->success([
            'next_step'       => 'pin_verification',
            'must_change_pin' => false,
            'email'           => $user->email,
        ], 'Login berhasil. Silakan masukkan PIN Anda.');
    }

    public function verifyPin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'pin'   => 'required|string|size:6',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) return $this->error('Akun tidak ditemukan.', 404);

        $pin = Pin::where('user_id', $user->id)->first();
        if (!$pin) return $this->error('PIN tidak ditemukan.', 404);

        if ($pin->isBlocked()) {
            return $this->error('PIN Anda diblokir sementara. Silakan coba lagi nanti.', 429);
        }

        if (!Hash::check($request->pin, $pin->pin)) {
            $attempts = $pin->pin_attempt + 1;
            $blockedUntil = $attempts >= 5 ? now()->addMinutes(30) : null;
            $pin->update([
                'pin_attempt'       => $attempts,
                'pin_blocked_until' => $blockedUntil,
            ]);
            return $this->error('PIN yang Anda masukkan salah.', 400, 'INVALID_PIN');
        }

        $pin->update(['pin_attempt' => 0, 'pin_blocked_until' => null]);

        $token = JWTAuth::fromUser($user);

        AuthLog::create([
            'user_id'    => $user->id,
            'event'      => 'login',
            'ip_address' => $request->ip(),
        ]);

        return $this->success([
            'token' => $token,
            'user'  => [
                'id'    => $user->id,
                'email' => $user->email,
                'roles' => $user->getRoleNames(),
            ],
            'settings' => [
                'must_change_pin' => $pin->must_change_pin,
            ],
        ], 'Login berhasil.');
    }

    public function resendPin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();
        $pin = Pin::where('user_id', $user->id)->first();
        if (!$pin) return $this->error('PIN tidak ditemukan.', 404);

        $newPin = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $pin->update([
            'pin'               => bcrypt($newPin),
            'pin_updated_at'    => now(),
            'pin_attempt'       => 0,
            'pin_blocked_until' => null,
        ]);

        Mail::to($user->email)->send(new PinResendMail($newPin, 10));

        return $this->success([
            'email'       => $user->email,
            'retry_after' => 60,
        ], 'PIN baru telah dikirim ke email Anda.');
    }

    public function me()
    {
        return $this->success(auth()->user()->load('profile'), 'Data pengguna.');
    }

    public function logout(Request $request)
    {
        $user = auth()->user();
        AuthLog::create([
            'user_id'    => $user->id,
            'event'      => 'logout',
            'ip_address' => $request->ip(),
        ]);
        JWTAuth::invalidate(JWTAuth::getToken());
        return $this->success(null, 'Logout berhasil.');
    }

    public function refresh()
    {
        $token = JWTAuth::refresh(JWTAuth::getToken());
        return $this->success(['token' => $token], 'Token diperbarui.');
    }
}
