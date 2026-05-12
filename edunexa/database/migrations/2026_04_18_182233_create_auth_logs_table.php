<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auth_logs', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('user_id')->constrained()->cascadeOnDelete();
            $table->string('event');
            $table->string('ip_address');
            $table->timestamps(); // This adds BOTH created_at and updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auth_logs');
    }
};
