<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\AuthLog;
use App\Models\Otp;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    use ApiResponse;

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) return $this->error('Ada yang salah dengan email ini', 404);

        Otp::where('user_id', $user->id)
            ->where('type', 'password_reset')
            ->whereNull('used_at')
            ->update(['used_at' => now()]);

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Otp::create([
            'user_id'    => $user->id,
            'email'      => $user->email,
            'code'       => bcrypt($code),
            'type'       => 'password_reset',
            'expired_at' => now()->addMinutes(5),
        ]);

        Mail::to($user->email)->send(new OtpMail($code, 5));

        return $this->success(null, 'Kode OTP telah dikirim ke email Anda');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code'  => 'required|string|size:6',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) return $this->error('Ada yang salah dengan email ini', 404);

        $otp = Otp::where('user_id', $user->id)
            ->where('type', 'password_reset')
            ->whereNull('used_at')
            ->latest()
            ->first();

        if (!$otp) return $this->error('Kode OTP tidak ditemukan', 422);
        if ($otp->isExpired()) return $this->error('Kode OTP sudah kedaluwarsa', 422);
        if (!Hash::check($request->code, $otp->code)) return $this->error('Kode OTP tidak valid', 422);

        $otp->update(['used_at' => now()]);

        $resetToken = Str::random(64);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => Hash::make($resetToken), 'created_at' => now()]
        );

        return $this->success(['reset_token' => $resetToken], 'Kode OTP valid');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'reset_token' => 'required|string',
            'password'    => 'required|min:8|confirmed',
        ]);

        $records = DB::table('password_reset_tokens')
            ->where('created_at', '>=', now()->subMinutes(10))
            ->get();

        $matched = null;
        foreach ($records as $row) {
            if (Hash::check($request->reset_token, $row->token)) {
                $matched = $row;
                break;
            }
        }

        if (!$matched) return $this->error('Token tidak valid atau sudah kedaluwarsa', 422);

        $user = User::where('email', $matched->email)->first();
        if (!$user) return $this->error('Ada yang salah dengan email ini', 404);

        $user->update(['password' => Hash::make($request->password)]);
        DB::table('password_reset_tokens')->where('email', $matched->email)->delete();

        AuthLog::create([
            'user_id'    => $user->id,
            'event'      => 'password_reset',
            'ip_address' => $request->ip(),
        ]);

        return $this->success(null, 'Password berhasil direset, silahkan login dengan password baru Anda');
    }
}
