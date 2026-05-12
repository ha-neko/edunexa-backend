<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $users = User::with('profile')
            ->filter($request)
            ->paginate($request->per_page ?? 10);

        return $this->success($users, 'Daftar pengguna');
    }

    public function show($id)
    {
        $user = User::with('profile')->find($id);

        if (!$user) {
            return $this->error('Pengguna tidak ditemukan', 404);
        }

        return $this->success($user, 'Detail pengguna');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'username' => 'nullable|string|unique:users',
            'status'   => 'nullable|in:Active,NonActive,Suspended,Banned',
            'role'     => 'nullable|string|exists:roles,name',
        ]);

        $user = User::create([
            'email'             => $request->email,
            'username'          => $request->username,
            'password'          => Hash::make($request->password),
            'status'            => $request->status ?? 'Active',
            'email_verified_at' => now(),
        ]);

        UserProfile::create(['user_id' => $user->id]);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $user->assignRole($request->role ?? 'user');

        return $this->success($user->load('profile'), 'Pengguna berhasil dibuat', 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->error('Pengguna tidak ditemukan', 404);
        }

        $request->validate([
            'email'    => 'nullable|email|unique:users,email,' . $id,
            'username' => 'nullable|string|unique:users,username,' . $id,
            'status'   => 'nullable|in:Active,NonActive,Suspended,Banned',
            'password' => 'nullable|min:8|confirmed',
            'fullname'      => 'nullable|string',
            'photo'         => 'nullable|string',
            'bio'           => 'nullable|string',
            'phone_number'  => 'nullable|string',
            'gender'        => 'nullable|in:Laki,Perempuan,Tidak Disebutkan',
            'birth_of_date' => 'nullable|date',
            'place_of_birth'=> 'nullable|string',
            'citizen'       => 'nullable|in:WNA,WNI',
            'address'       => 'nullable|string',
        ]);

        $user->update([
            'email'    => $request->email ?? $user->email,
            'username' => $request->username ?? $user->username,
            'status'   => $request->status ?? $user->status,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        $user->profile->update($request->only([
            'fullname', 'photo', 'bio', 'phone_number',
            'gender', 'birth_of_date', 'place_of_birth',
            'citizen', 'country_id', 'province_id', 'city_id',
            'district_id', 'village_id', 'postal_code',
            'rt', 'rw', 'address',
        ]));

        return $this->success($user->load('profile'), 'Pengguna berhasil diperbarui');
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->error('Pengguna tidak ditemukan', 404);
        }

        $user->delete();

        return $this->success(null, 'Pengguna berhasil dihapus');
    }

    public function restore($id)
    {
        $user = User::withTrashed()->find($id);

        if (!$user) {
            return $this->error('Pengguna tidak ditemukan', 404);
        }

        $user->restore();

        return $this->success($user, 'Pengguna berhasil dipulihkan');
    }
}
