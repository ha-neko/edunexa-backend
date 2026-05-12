<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('user_id')->constrained()->cascadeOnDelete();
            $table->string('fullaname')->nullable();
            $table->string('photo')->nullable();
            $table->text('bio')->nullable();
            $table->string('phone', 20)->nullable();
            $table->enum('gender', ['Laki-Laki', 'Perempuan', 'Tidak Disebutkan'])->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->foreignUlid('village_id')->nullable()->constrained('villages')->nullOnDelete();
            $table->integer('postal_code')->nullable();
            $table->tinyInteger('rt')->nullable();
            $table->tinyInteger('rw')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
