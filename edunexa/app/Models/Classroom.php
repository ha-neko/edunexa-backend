<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classroom extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $fillable = [
        'major_id',
        'grade',
        'group_number',
        'academic_year',
        'wali_kelas_id',
    ];

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    // ── Relations ────────────────────────────────────────────────────────

    public function major(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Major::class)->withTrashed();
    }

    public function waliKelas(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'wali_kelas_id')->withTrashed();
    }

    public function students(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function attendances(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Attendance::class, Student::class);
    }

    /**
     * Label tampilan kelas, misal: "X RPL 1 (2024/2025)"
     */
    public function getLabelAttribute(): string
    {
        return "{$this->grade} {$this->major?->major_code} {$this->group_number} ({$this->academic_year})";
    }
}
