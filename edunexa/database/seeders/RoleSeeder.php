<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// JANGAN PAKAI: use Spatie\Permission\Models\Role;
use App\Models\Role; // PAKAI YANG INI

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['user', 'pegawai', 'admin', 'super admin'];

        foreach ($roles as $role) {
            // Karena ini App\Models\Role, dia akan memicu HasUlids
            Role::updateOrCreate(
                ['name' => $role],
                ['guard_name' => 'web']
            );
        }
    }
}