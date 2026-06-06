<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $fillable = [
        'student_id',
        'attendance_date',
        'scan_in',
        'scan_out',
        'status',
        'updated_by',
        'notes',
        'photo',
    ];

    protected function casts(): array
    {
        return [
            'attendance_date' => 'date',
            'deleted_at'      => 'datetime',
        ];
    }

    // ── Relations ────────────────────────────────────────────────────────

    public function student(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Student::class)->withTrashed();
    }

    public function updatedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by')->withTrashed();
    }

    // ── Scopes ───────────────────────────────────────────────────────────

    public function scopeByDate($query, string $date)
    {
        return $query->whereDate('attendance_date', $date);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByClassroom($query, string $classroomId)
    {
        return $query->whereHas('student', fn ($q) => $q->where('classroom_id', $classroomId));
    }
}
