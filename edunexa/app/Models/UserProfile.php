<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasUlids;

    protected $fillable = [
        'user_id', 'fullaname', 'photo', 'bio', 'phone',
        'gender', 'tanggal_lahir', 'tempat_lahir',
        'village_id', 'postal_code', 'rt', 'rw', 'address',
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
