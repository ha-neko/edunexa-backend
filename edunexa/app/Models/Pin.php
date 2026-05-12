<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Pin extends Model
{
    use HasUlids;

    protected $fillable = [
        'user_id',
        'pin',
        'must_change_pin',
        'pin_attempt',
        'pin_blocked_until',
        'pin_updated_at',
    ];

    protected $hidden = ['pin'];

    protected $casts = [
        'must_change_pin'   => 'boolean',
        'pin_blocked_until' => 'datetime',
        'pin_updated_at'    => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isBlocked(): bool
    {
        return $this->pin_blocked_until && now()->isBefore($this->pin_blocked_until);
    }
}
