<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $roles = Role::where('guard_name', 'api')->get();
        return $this->success($roles, 'Daftar role');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
        ]);

        $role = Role::create([
            'id'         => (string) Str::ulid(),
            'name'       => $request->name,
            'guard_name' => 'api',
        ]);

        return $this->success($role, 'Role berhasil dibuat', 201);
    }

    public function destroy($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return $this->error('Role tidak ditemukan', 404);
        }

        $role->delete();

        return $this->success(null, 'Role berhasil dihapus');
    }

    public function assignToUser(Request $request, $userId)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = \App\Models\User::find($userId);
        if (!$user) return $this->error('Pengguna tidak ditemukan', 404);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $user->assignRole($request->role);

        return $this->success(null, 'Role berhasil diberikan ke pengguna');
    }

    public function removeFromUser(Request $request, $userId)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = \App\Models\User::find($userId);
        if (!$user) return $this->error('Pengguna tidak ditemukan', 404);

        $user->removeRole($request->role);

        return $this->success(null, 'Role berhasil dihapus dari pengguna');
    }

    public function givePermission(Request $request, $id)
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $role = Role::find($id);

        if (!$role) {
            return $this->error('Role tidak ditemukan', 404);
        }

        // Menghapus cache permission agar perubahan langsung terasa
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // syncPermissions akan menghapus permission lama dan menggantinya dengan yang baru
        $role->syncPermissions($request->permissions);

        return $this->success(
            $role->load('permissions'), 
            'Permissions berhasil diperbarui untuk role ' . $role->name
        );
    }
}
