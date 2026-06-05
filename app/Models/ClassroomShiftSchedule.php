<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassroomShiftSchedule extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $fillable = [
        'classroom_id',
        'shift_id',
        'day_of_week',
    ];

    public function classroom(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Classroom::class)->withTrashed();
    }

    public function shift(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Shift::class)->withTrashed();
    }

    public function getDayNameAttribute(): string
    {
        return ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][$this->day_of_week];
    }
}
