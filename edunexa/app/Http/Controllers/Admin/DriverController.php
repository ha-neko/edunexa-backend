<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Employee;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $drivers = Driver::with('employee.user.profile')
            ->paginate($request->per_page ?? 10);
        return $this->success($drivers, 'Daftar driver.');
    }

    public function show($id)
    {
        $driver = Driver::with('employee.user.profile')->find($id);
        if (!$driver) return $this->error('Driver tidak ditemukan.', 404);
        return $this->success($driver, 'Detail driver.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id'       => 'required|exists:employees,id',
            'model_kendaraan'  => 'nullable|string',
            'jenis_kendaraan'  => 'nullable|in:motor,mobil,truck',
            'nomor_kendaraan'  => 'required|string',
            'sim'              => 'nullable|string',
        ]);

        $driver = Driver::create($request->only(
            'pegawai_id', 'model_kendaraan',
            'jenis_kendaraan', 'nomor_kendaraan', 'sim'
        ));

        return $this->success($driver->load('employee.user.profile'), 'Driver berhasil dibuat.', 201);
    }

    public function update(Request $request, $id)
    {
        $driver = Driver::find($id);
        if (!$driver) return $this->error('Driver tidak ditemukan.', 404);

        $request->validate([
            'model_kendaraan' => 'nullable|string',
            'jenis_kendaraan' => 'nullable|in:motor,mobil,truck',
            'nomor_kendaraan' => 'nullable|string',
            'sim'             => 'nullable|string',
        ]);

        $driver->update($request->only(
            'model_kendaraan', 'jenis_kendaraan', 'nomor_kendaraan', 'sim'
        ));

        return $this->success($driver->load('employee.user.profile'), 'Driver berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $driver = Driver::find($id);
        if (!$driver) return $this->error('Driver tidak ditemukan.', 404);
        $driver->delete();
        return $this->success(null, 'Driver berhasil dihapus.');
    }

    public function restore($id)
    {
        $driver = Driver::withTrashed()->find($id);
        if (!$driver) return $this->error('Driver tidak ditemukan.', 404);
        $driver->restore();
        return $this->success($driver, 'Driver berhasil dipulihkan.');
    }

    public function updateStatus(Request $request, $id)
    {
        $driver = Driver::find($id);
        if (!$driver) return $this->error('Driver tidak ditemukan.', 404);

        $request->validate([
            'status' => 'required|in:Active,NonActive,Suspended,Banned',
        ]);

        $driver->employee->user->update(['status' => $request->status]);
        return $this->success(null, 'Status driver berhasil diperbarui.');
    }
}
