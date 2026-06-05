<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah status jadi string biar bisa nambah 'telat'
        DB::statement("ALTER TABLE attendances MODIFY status VARCHAR(20) DEFAULT 'alpha'");

        Schema::table('attendances', function (Blueprint $table) {
            $table->string('photo', 255)->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('photo');
        });
    }
};
