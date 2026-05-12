<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// INI YANG PENTING:
use Spatie\LaravelSettings\SettingsMigrator; 

class DatabaseSeeder extends Seeder
{
 public function run(): void
{
    $this->call(RoleSeeder::class);

    // Langsung tembak ke tabel settings
    \Illuminate\Support\Facades\DB::table('settings')->insertOrIgnore([
        ['group' => 'app', 'name' => 'app_name', 'payload' => json_encode('Bayn'), 'created_at' => now(), 'updated_at' => now()],
        ['group' => 'app', 'name' => 'app_version', 'payload' => json_encode('1.0.0'), 'created_at' => now(), 'updated_at' => now()],
        ['group' => 'app', 'name' => 'app_description', 'payload' => json_encode('Food Ordering System'), 'created_at' => now(), 'updated_at' => now()],
    ]);
}
}