<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class AppSettings extends Settings
{
    public string $app_name;
    public string $app_version;
    public string $app_description;

    public static function group(): string
    {
        return 'app';
    }
}
