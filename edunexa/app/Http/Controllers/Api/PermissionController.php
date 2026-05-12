<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $permissions = Permission::where('guard_name', 'api')->get();
        return $this->success($permissions, 'Daftar permission');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ]);

        $permission = Permission::create([
            'id'         => (string) Str::ulid(),
            'name'       => $request->name,
            'guard_name' => 'api',
        ]);

        return $this->success($permission, 'Permission berhasil dibuat', 201);
    }

    public function destroy($id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return $this->error('Permission tidak ditemukan', 404);
        }

        $permission->delete();

        return $this->success(null, 'Permission berhasil dihapus');
    }

    public function assignToUser(Request $request, $userId)
    {
        $request->validate([
            'permission' => 'required|string|exists:permissions,name',
        ]);

        $user = \App\Models\User::find($userId);
        if (!$user) return $this->error('Pengguna tidak ditemukan', 404);

        $user->givePermissionTo($request->permission);

        return $this->success(null, 'Permission berhasil diberikan ke pengguna');
    }
}
