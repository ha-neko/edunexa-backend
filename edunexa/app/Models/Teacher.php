<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nip',
        'specialization',
    ];

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    // ── Relations ────────────────────────────────────────────────────────

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function classrooms(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Classroom::class, 'wali_kelas_id');
    }

    public function isWaliKelasOf(string $classroomId): bool
    {
        return $this->classrooms()->where('id', $classroomId)->exists();
    }
}
