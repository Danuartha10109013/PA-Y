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
        Schema::table('pengajuan_pinjamans', function (Blueprint $table) {
            $table->decimal('score', 5, 2)->nullable()->after('sisa_jangka_waktu'); // Skor SPK
            $table->string('level', 50)->nullable()->after('score'); // Level SPK
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_pinjamans', function (Blueprint $table) {
            $table->dropColumn(['score', 'level']);
        });
    }
};
