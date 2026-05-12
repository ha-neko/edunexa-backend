<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['group' => 'app', 'name' => 'app_name',        'payload' => json_encode('Bayn')],
            ['group' => 'app', 'name' => 'app_version',     'payload' => json_encode('1.0.0')],
            ['group' => 'app', 'name' => 'app_description', 'payload' => json_encode('Platform Terpercaya Anda')],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['group' => $setting['group'], 'name' => $setting['name']],
                ['payload' => $setting['payload'], 'locked' => false]
            );
        }
    }
}
