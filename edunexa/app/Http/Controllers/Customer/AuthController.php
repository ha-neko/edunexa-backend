<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
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
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|unique:users',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'email'    => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'status'   => 'Active',
        ]);

        UserProfile::create([
            'user_id'  => $user->id,
            'fullname' => $request->fullname,
        ]);

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

        return $this->success([
            'user_id'   => $user->id,
            'email'     => $user->email,
            'next_step' => 'otp_verification',
        ], 'Registrasi berhasil. Silakan cek email Anda untuk kode verifikasi.', 201);
    }

    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code'  => 'required|string|size:6',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) return $this->error('Akun dengan email tersebut tidak ditemukan.', 404, 'EMAIL_NOT_FOUND');

        $otp = Otp::where('user_id', $user->id)
            ->where('type', 'email_verification')
            ->whereNull('used_at')
            ->latest()
            ->first();

        if (!$otp) return $this->error('Kode verifikasi yang Anda masukkan salah.', 400, 'INVALID_OTP_CODE');
        if ($otp->isExpired()) return $this->error('Kode verifikasi telah kedaluwarsa. Silakan minta kode baru.', 400, 'OTP_EXPIRED');
        if (!Hash::check($request->code, $otp->code)) return $this->error('Kode verifikasi yang Anda masukkan salah.', 400, 'INVALID_OTP_CODE');

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
            'user'  => [
                'id'     => $user->id,
                'status' => $user->status,
            ],
        ], 'Email berhasil diverifikasi. Akun Anda kini aktif.');
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->email_verified_at) {
            return $this->error('Email sudah terverifikasi.', 400);
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

        return $this->success([
            'email'       => $user->email,
            'retry_after' => 60,
        ], 'Kode verifikasi baru telah dikirim ke email Anda.');
    }

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

        // Check role — customer only
        if (!$user->hasRole('user')) {
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
                'must_change_pin' => false,
            ],
        ], 'Login berhasil.');
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
