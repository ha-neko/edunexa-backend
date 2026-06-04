<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HasUlids;

    // Ensure the primary key type is set to string for ULIDs
    protected $keyType = 'string';
    public $incrementing = false;
}