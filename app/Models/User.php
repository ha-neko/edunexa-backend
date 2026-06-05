<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;



class User extends Authenticatable implements JWTSubject
{
    use HasFactory, HasUlids, HasRoles, Notifiable, SoftDeletes;

    protected $guard_name = 'api'; 

    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getProfilePhotoUrlAttribute(): ?string
    {
        return $this->photo ? asset('storage/' . $this->photo) : null;
    }

    protected function casts(): array
    {
        return [
            'password'          => 'hashed',
            'deleted_at'        => 'datetime',
        ];
    }

    // ── JWT ──────────────────────────────────────────────────────────────

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            // Sertakan role pertama di JWT payload supaya client tidak
            // perlu request ulang hanya untuk tahu role-nya.
            'role' => $this->getRoleNames()->first(),
        ];
    }

    // ── Relations ────────────────────────────────────────────────────────

    public function teacher(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Teacher::class);
    }

    public function student(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function guardian(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Guardian::class);
    }

    // ── Helpers ──────────────────────────────────────────────────────────

    /**
     * Resolve profil berdasarkan role aktif user.
     * Berguna di controller untuk tidak perlu if-else manual.
     *
     * @return Teacher|Student|Guardian|null
     */
    public function profile(): Teacher|Student|Guardian|null
    {
        return match (true) {
            $this->hasRole('guru')     => $this->teacher,
            $this->hasRole('siswa')    => $this->student,
            $this->hasRole('guardian') => $this->guardian,
            default                    => null,
        };
    }
}