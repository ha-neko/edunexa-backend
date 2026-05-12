<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class RefreshToken extends Model
{
    use HasUlids;

    protected $fillable = [
        'user_id', 'token', 'is_revoked',
        'ip_address', 'expires_at', 'user_agent',
    ];

    protected $casts = [
        'is_revoked' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
