<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Mail\VerifyEmailMail;
use App\Models\AuthLog;
use App\Models\Otp;
use App\Models\User;
use App\Models\UserProfile;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'username' => 'nullable|string|unique:users',
        ]);

        $user = User::create([
            'email'    => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'status'   => 'Active',
        ]);

        UserProfile::create(['user_id' => $user->id]);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $user->assignRole('user');

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Otp::create([
            'user_id'    => $user->id,
            'email'      => $user->email,
            'code'       => bcrypt($code),
            'type'       => 'email_verification',
            'expired_at' => now()->addMinutes(5),
        ]);
        Mail::to($user->email)->send(new VerifyEmailMail($code, 5));

        AuthLog::create([
            'user_id'    => $user->id,
            'event'      => 'register',
            'ip_address' => $request->ip(),
        ]);

        return $this->success(null, 'Anda telah berhasil terdaftar, silahkan buka email untuk verifikasi OTP untuk melanjutkan', 201, false);
    }

    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code'  => 'required|string|size:6',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) return $this->error('Ada yang salah dengan email ini', 404);

        $otp = Otp::where('user_id', $user->id)
            ->where('type', 'email_verification')
            ->whereNull('used_at')
            ->latest()
            ->first();

        if (!$otp) return $this->error('Kode OTP tidak ditemukan', 422);
        if ($otp->isExpired()) return $this->error('Kode OTP sudah kedaluwarsa', 422);
        if (!Hash::check($request->code, $otp->code)) return $this->error('Kode OTP tidak valid', 422);

        $otp->update(['used_at' => now()]);
        $user->update(['email_verified_at' => now()]);

        $token = JWTAuth::fromUser($user);

        AuthLog::create([
            'user_id'    => $user->id,
            'event'      => 'email_verified',
            'ip_address' => $request->ip(),
        ]);

        return $this->success([
            'token' => $token,
            'user'  => $user,
        ], 'Email berhasil diverifikasi', 200, false);
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->email_verified_at) {
            return $this->error('Email sudah terverifikasi', 400);
        }

        Otp::where('user_id', $user->id)
            ->where('type', 'email_verification')
            ->whereNull('used_at')
            ->update(['used_at' => now()]);

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Otp::create([
            'user_id'    => $user->id,
            'email'      => $user->email,
            'code'       => bcrypt($code),
            'type'       => 'email_verification',
            'expired_at' => now()->addMinutes(5),
        ]);

        Mail::to($user->email)->send(new VerifyEmailMail($code, 5));

        return $this->success(null, 'Kode OTP baru telah dikirim ke email Anda', 200, false);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
            return $this->error('Email atau password salah', 401);
        }

        $user = auth()->user();

        if ($user->status !== 'Active') {
          auth()->logout();
         return $this->error('Akun Anda tidak aktif', 403);
        }
        if (!$user->email_verified_at) {
            JWTAuth::invalidate(JWTAuth::getToken());
            return $this->error('Silahkan verifikasi email Anda terlebih dahulu', 403);
        }

        AuthLog::create([
            'user_id'    => $user->id,
            'event'      => 'login',
            'ip_address' => $request->ip(),
        ]);

        return $this->success([
            'token' => $token,
            'user'  => $user,
        ], 'Login berhasil', 200, false);
    }

    public function me()
    {
        return $this->success(auth()->user()->load('profile'), 'Data pengguna');
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

        return $this->success(null, 'Logout berhasil');
    }

    public function refresh()
    {
        $token = JWTAuth::refresh(JWTAuth::getToken());
        return $this->success(['token' => $token], 'Token diperbarui');
    }
}
