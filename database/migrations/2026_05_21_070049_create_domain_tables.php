<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Permission;
use App\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        // ── majors ────────────────────────────────────────────────────
        Schema::create('majors', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('major_code', 10)->unique();
            $table->string('major_name', 100);
            $table->softDeletes();
            $table->timestamps();
        });

        // ── guardians ─────────────────────────────────────────────────
        Schema::create('guardians', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('user_id')->unique();
            $table->string('phone_number', 20)->nullable();
            $table->text('address')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });

        // ── teachers ──────────────────────────────────────────────────
        Schema::create('teachers', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('user_id')->unique();
            $table->string('nip', 50)->nullable()->unique();
            $table->string('specialization', 100)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });

        // ── classrooms ────────────────────────────────────────────────
        Schema::create('classrooms', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('major_id');
            $table->enum('grade', ['X', 'XI', 'XII']);
            $table->string('group_number', 10);
            $table->string('academic_year', 10);
            $table->ulid('wali_kelas_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('major_id')
                  ->references('id')->on('majors')
                  ->onDelete('restrict');

            $table->foreign('wali_kelas_id')
                  ->references('id')->on('teachers')
                  ->onDelete('set null');
        });

        // ── students ──────────────────────────────────────────────────
        Schema::create('students', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('user_id')->unique();
            $table->string('nis', 20)->unique();
            $table->ulid('classroom_id');
            $table->ulid('guardian_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->foreign('classroom_id')
                  ->references('id')->on('classrooms')
                  ->onDelete('restrict');

            $table->foreign('guardian_id')
                  ->references('id')->on('guardians')
                  ->onDelete('set null');
        });

        // ── attendances ───────────────────────────────────────────────
        Schema::create('attendances', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('student_id');
            $table->date('attendance_date');
            $table->time('scan_in')->nullable();
            $table->time('scan_out')->nullable();
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpha'])->default('alpha');
            $table->ulid('updated_by')->nullable();
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // Satu siswa hanya satu record per hari (ignore soft-deleted)
            $table->unique(['student_id', 'attendance_date']);
            $table->index('attendance_date');

            $table->foreign('student_id')
                  ->references('id')->on('students')
                  ->onDelete('cascade');

            $table->foreign('updated_by')
                  ->references('id')->on('users')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('students');
        Schema::dropIfExists('classrooms');
        Schema::dropIfExists('teachers');
        Schema::dropIfExists('guardians');
        Schema::dropIfExists('majors');
    }
};