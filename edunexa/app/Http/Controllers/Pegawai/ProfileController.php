<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Pin;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    use ApiResponse;

    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'fullaname'     => 'nullable|string|max:255',
            'bio'           => 'nullable|string',
            'phone'         => 'nullable|string|max:20',
            'gender'        => 'nullable|in:Laki-Laki,Perempuan,Tidak Disebutkan',
            'tanggal_lahir' => 'nullable|date_format:Y-m-d',
            'tempat_lahir'  => 'nullable|string',
            'address'       => 'nullable|string',
        ]);

        $profile = $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        return $this->success($profile, 'Profil berhasil diperbarui.');
    }

    public function updatePhoto(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $profile = $user->profile ?: $user->profile()->create(['fullaname' => $user->username]);

        if ($profile->photo) {
            Storage::disk('public')->delete($profile->photo);
        }

        $path = $request->file('photo')->store('profiles', 'public');
        $profile->update(['photo' => $path]);

        return $this->success([
            'photo_url' => asset('storage/' . $path),
        ], 'Foto profil berhasil diperbarui.');
    }

    public function changePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return $this->error('Password lama yang Anda masukkan salah.', 400, 'INVALID_OLD_PASSWORD');
        }

        $user->update(['password' => Hash::make($request->password)]);

        return $this->success(null, 'Password berhasil diperbarui.');
    }

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
