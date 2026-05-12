<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Village extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = ['district_id', 'code', 'name'];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function profiles()
    {
        return $this->hasMany(UserProfile::class);
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }
}
