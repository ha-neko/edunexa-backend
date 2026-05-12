<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('pegawai_id')->constrained('employees')->cascadeOnDelete();
            $table->string('model_kendaraan')->nullable();
            $table->enum('jenis_kendaraan', ['motor', 'mobil', 'truck'])->nullable();
            $table->string('nomor_kendaraan');
            $table->string('sim')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
