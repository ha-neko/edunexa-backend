<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PinInitialMail;
use App\Models\Employee;
use App\Models\Pin;
use App\Models\User;
use App\Models\UserProfile;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class EmployeeController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $employees = Employee::with('user.profile')
            ->paginate($request->per_page ?? 10);
        return $this->success($employees, 'Daftar pegawai.');
    }

    public function show($id)
    {
        $employee = Employee::with('user.profile', 'driver')->find($id);
        if (!$employee) return $this->error('Pegawai tidak ditemukan.', 404);
        return $this->success($employee, 'Detail pegawai.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|unique:users',
            'username' => 'required|string|unique:users',
            'password' => 'required|min:8|confirmed',
            'jabatan'  => 'nullable|string',
            'divisi'   => 'nullable|string',
            'fullaname'=> 'nullable|string',
        ]);

        $user = User::create([
            'email'             => $request->email,
            'username'          => $request->username,
            'password'          => Hash::make($request->password),
            'status'            => 'Active',
            'email_verified_at' => now(),
        ]);

        UserProfile::create([
            'user_id'  => $user->id,
            'fullaname'=> $request->fullaname,
        ]);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $user->assignRole('pegawai');

        $employee = Employee::create([
            'user_id' => $user->id,
            'jabatan' => $request->jabatan,
            'divisi'  => $request->divisi,
        ]);

        // Generate initial PIN
        $newPin = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Pin::create([
            'user_id'         => $user->id,
            'pin'             => bcrypt($newPin),
            'must_change_pin' => true,
            'pin_attempt'     => 0,
            'pin_updated_at'  => now(),
        ]);

        Mail::to($user->email)->send(new PinInitialMail($newPin, 10));

        return $this->success($employee->load('user.profile'), 'Pegawai berhasil dibuat.', 201);
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);
        if (!$employee) return $this->error('Pegawai tidak ditemukan.', 404);

        $request->validate([
            'jabatan' => 'nullable|string',
            'divisi'  => 'nullable|string',
            'fullaname'      => 'nullable|string',
            'phone'          => 'nullable|string',
            'gender'         => 'nullable|in:Laki-Laki,Perempuan,Tidak Disebutkan',
            'tanggal_lahir'  => 'nullable|date',
            'tempat_lahir'   => 'nullable|string',
            'address'        => 'nullable|string',
        ]);

        $employee->update($request->only('jabatan', 'divisi'));
        $employee->user->profile->update($request->only(
            'fullaname', 'phone', 'gender',
            'tanggal_lahir', 'tempat_lahir', 'address'
        ));

        return $this->success($employee->load('user.profile'), 'Pegawai berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $employee = Employee::find($id);
        if (!$employee) return $this->error('Pegawai tidak ditemukan.', 404);
        $employee->delete();
        return $this->success(null, 'Pegawai berhasil dihapus.');
    }

    public function restore($id)
    {
        $employee = Employee::withTrashed()->find($id);
        if (!$employee) return $this->error('Pegawai tidak ditemukan.', 404);
        $employee->restore();
        return $this->success($employee, 'Pegawai berhasil dipulihkan.');
    }

    public function updateStatus(Request $request, $id)
    {
        $employee = Employee::find($id);
        if (!$employee) return $this->error('Pegawai tidak ditemukan.', 404);

        $request->validate([
            'status' => 'required|in:Active,NonActive,Suspended,Banned',
        ]);

        $employee->user->update(['status' => $request->status]);
        return $this->success(null, 'Status pegawai berhasil diperbarui.');
    }

    public function generatePin($id)
    {
        $employee = Employee::find($id);
        if (!$employee) return $this->error('Pegawai tidak ditemukan.', 404);

        $newPin = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $pin = Pin::updateOrCreate(
            ['user_id' => $employee->user_id],
            [
                'pin'             => bcrypt($newPin),
                'must_change_pin' => true,
                'pin_attempt'     => 0,
                'pin_updated_at'  => now(),
            ]
        );

        Mail::to($employee->user->email)->send(new PinInitialMail($newPin, 10));

        return $this->success(null, 'PIN baru telah dikirim ke email pegawai.');
    }
}
