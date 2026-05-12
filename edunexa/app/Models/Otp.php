<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasUlids;

    protected $fillable = [
        'user_id', 'email', 'code', 'type', 'expired_at', 'used_at', 'attempts',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'used_at'    => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return now()->isAfter($this->expired_at);
    }

    public function isUsed(): bool
    {
        return !is_null($this->used_at);
    }

    public function isValid(): bool
    {
        return !$this->isUsed() && !$this->isExpired();
    }
}
