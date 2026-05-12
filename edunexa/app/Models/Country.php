<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = ['name', 'iso2', 'iso3', 'phone_code'];

    public function provinces()
    {
        return $this->hasMany(Province::class);
    }
}
