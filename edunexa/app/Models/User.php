<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes, LogsActivity, HasUlids;

    protected $fillable = [
        'google_id',
        'username',
        'email',
        'password',
        'email_verified_at',
        'status',
        'access_token',
        'expired_token_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'access_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'expired_token_at'  => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function otps()
    {
        return $this->hasMany(Otp::class);
    }

    public function authLogs()
    {
        return $this->hasMany(AuthLog::class);
    }

    public function refreshTokens()
    {
        return $this->hasMany(RefreshToken::class);
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    public function deviceTokens()
    {
        return $this->hasMany(DeviceToken::class);
    }

    public function scopeFilter($query, $request)
    {
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('email', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        return $query;
    }
    public function employee()
    {
    return $this->hasOne(Employee::class);
  }
}
