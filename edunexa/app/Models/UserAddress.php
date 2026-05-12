<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAddress extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'user_id', 'label', 'is_primary', 'receiver_name',
        'receiver_phone', 'address_detail', 'latitude',
        'longitude', 'note_to_driver', 'village_id',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'latitude'   => 'decimal:8',
        'longitude'  => 'decimal:8',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function village()
    {
        return $this->belongsTo(Village::class);
    }
}
