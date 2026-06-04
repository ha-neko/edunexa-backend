<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassroomShiftSchedule extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'classroom_id',
        'shift_id',
        'day_of_week',
    ];

    // ── Relations ────────────────────────────────────────────────────────

    public function classroom(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Classroom::class)->withTrashed();
    }

    public function shift(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Shift::class)->withTrashed();
    }

    // ── Helpers ──────────────────────────────────────────────────────────

    /**
     * Ambil nama hari dalam Bahasa Indonesia.
     */
    public function getDayNameAttribute(): string
    {
        return ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][$this->day_of_week];
    }
}
