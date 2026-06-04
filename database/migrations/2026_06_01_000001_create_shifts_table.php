<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── shifts ────────────────────────────────────────────────────
        // Menyimpan definisi shift: nama, jam masuk, jam toleransi, jam keluar
        Schema::create('shifts', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name', 50);                     // "Shift Pagi", "Shift Siang"
            $table->time('start_time');                     // Jam masuk resmi, e.g. 07:00:00
            $table->time('late_tolerance');                 // Batas toleransi terlambat, e.g. 07:15:00
            $table->time('end_time');                       // Jam pulang, e.g. 13:00:00
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        // ── classroom_shift_schedules ─────────────────────────────────
        // Jadwal shift per kelas: kelas A hari Senin-Rabu pakai shift siang, dll.
        // Satu kelas bisa punya banyak baris (satu per hari dalam seminggu).
        Schema::create('classroom_shift_schedules', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('classroom_id');
            $table->ulid('shift_id');
            // 0=Minggu, 1=Senin, 2=Selasa, 3=Rabu, 4=Kamis, 5=Jumat, 6=Sabtu
            $table->tinyInteger('day_of_week');
            $table->softDeletes();
            $table->timestamps();

            // Satu kelas hanya boleh satu shift per hari
            $table->unique(['classroom_id', 'day_of_week', 'deleted_at']);

            $table->foreign('classroom_id')
                  ->references('id')->on('classrooms')
                  ->onDelete('cascade');

            $table->foreign('shift_id')
                  ->references('id')->on('shifts')
                  ->onDelete('restrict');
        });

        // ── Tambah kolom ke tabel students: qr_token ──────────────────
        // QR token dibuat SEKALI saat siswa dibuat, tidak pernah berubah
        // kecuali admin reset manual.
        Schema::table('students', function (Blueprint $table) {
            $table->string('qr_token', 64)->nullable()->unique()->after('guardian_id');
        });

        // ── Tambah kolom ke tabel attendances: shift_id ───────────────
        Schema::table('attendances', function (Blueprint $table) {
            $table->ulid('shift_id')->nullable()->after('attendance_date');
            $table->foreign('shift_id')
                  ->references('id')->on('shifts')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['shift_id']);
            $table->dropColumn('shift_id');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('qr_token');
        });

        Schema::dropIfExists('classroom_shift_schedules');
        Schema::dropIfExists('shifts');
    }
};
