<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->ulid('id')->primary(); // ID Absen pakai ULID
            $table->ulid('user_id');      // ID User juga ULID (Foreign Key)
            $table->timestamp('waktu_masuk');
            $table->timestamp('waktu_keluar')->nullable(); // Boleh kosong dulu pas baru masuk
            $table->string('status');      // 'hadir', 'izin', atau 'sakit'
            $table->text('keterangan')->nullable(); // Buat alasan kalau izin/sakit
            $table->timestamps();

            // Relasi ke tabel users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
