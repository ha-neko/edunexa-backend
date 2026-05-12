<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('province_id')->constrained()->cascadeOnDelete();
            $table->string('code', 10);
            $table->string('name')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['code', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
