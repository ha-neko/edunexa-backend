<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Student extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nis',
        'classroom_id',
        'guardian_id',
        'qr_token',
    ];

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    // ── Boot: generate QR token otomatis saat create ──────────────────

    protected static function booted(): void
    {
        static::creating(function (Student $student) {
            // Generate sekali, tidak berubah kecuali di-reset manual
            $student->qr_token ??= hash('sha256', Str::uuid() . $student->nis . now());
        });
    }

    // ── Relations ────────────────────────────────────────────────────────

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function classroom(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Classroom::class)->withTrashed();
    }

    public function guardian(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Guardian::class)->withTrashed();
    }

    public function attendances(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function todayAttendance(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Attendance::class)
                    ->whereDate('attendance_date', today());
    }

    // ── Helpers ──────────────────────────────────────────────────────────

    /**
     * Ambil shift aktif untuk hari ini berdasarkan jadwal kelas.
     */
    public function getTodayShift(): ?Shift
    {
        $dayOfWeek = now()->dayOfWeek; // 0=Minggu ... 6=Sabtu

        return ClassroomShiftSchedule::with('shift')
            ->where('classroom_id', $this->classroom_id)
            ->where('day_of_week', $dayOfWeek)
            ->first()
            ?->shift;
    }

    /**
     * Regenerate QR token (hanya admin yang boleh panggil ini).
     */
    public function regenerateQrToken(): string
    {
        $token = hash('sha256', Str::uuid() . $this->nis . now());
        $this->update(['qr_token' => $token]);
        return $token;
    }
}
