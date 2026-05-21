<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

// ── CHANGE THESE TWO LINES TO POINT TO YOUR CUSTOM MODELS ──────────────────
use App\Models\Permission;
use App\Models\Role;
// ───────────────────────────────────────────────────────────────────────────

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles & permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ── Permissions ───────────────────────────────────────────────
        $permissions = [
            // User management
            'manage-users',

            // Major
            'manage-majors',
            'view-majors',

            // Classroom
            'manage-classrooms',
            'view-classrooms',

            // Teacher
            'manage-teachers',
            'view-teachers',

            // Student
            'manage-students',
            'view-students',

            // Guardian
            'manage-guardians',
            'view-guardians',

            // Attendance
            'manage-attendances',        // admin & guru wali kelas
            'view-all-attendances',      // admin & guru
            'view-own-attendance',       // siswa
            'view-ward-attendance',      // guardian (lihat absensi anak wali)

            // Report
            'view-reports',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(
                ['name' => $perm, 'guard_name' => 'api']
            );
        }

        // ── Roles ─────────────────────────────────────────────────────
        $roleAdmin    = Role::firstOrCreate(['name' => 'admin',    'guard_name' => 'api']);
        $roleGuru     = Role::firstOrCreate(['name' => 'guru',     'guard_name' => 'api']);
        $roleSiswa    = Role::firstOrCreate(['name' => 'siswa',    'guard_name' => 'api']);
        $roleGuardian = Role::firstOrCreate(['name' => 'guardian', 'guard_name' => 'api']);

        // Admin: semua permission
        $roleAdmin->syncPermissions(Permission::all());

        // Guru: kelola absensi kelas + lihat data
        $roleGuru->syncPermissions([
            'view-majors',
            'view-classrooms',
            'view-teachers',
            'view-students',
            'view-guardians',
            'manage-attendances',
            'view-all-attendances',
            'view-reports',
        ]);

        // Siswa: hanya lihat absensi sendiri
        $roleSiswa->syncPermissions([
            'view-own-attendance',
        ]);

        // Guardian: hanya lihat absensi anak walinya
        $roleGuardian->syncPermissions([
            'view-ward-attendance',
        ]);

        // ── Default Admin Account ─────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@smkcs.sch.id'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('admin123!'),
            ]
        );

        $admin->assignRole('admin');
    }
}
