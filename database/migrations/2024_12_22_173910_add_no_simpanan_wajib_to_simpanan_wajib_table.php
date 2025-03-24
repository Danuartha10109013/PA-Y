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
        Schema::table('simpanan_wajib', function (Blueprint $table) {
            $table->string('no_simpanan_wajib', 50)->nullable()->after('bank')->comment('Nomor unik untuk simpanan wajib');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('simpanan_wajib', function (Blueprint $table) {
            //
        });
    }
};
