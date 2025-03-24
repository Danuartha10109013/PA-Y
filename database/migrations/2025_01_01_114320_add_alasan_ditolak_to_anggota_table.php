<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAlasanDitolakToAnggotaTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->string('alasan_ditolak')->nullable()->after('status_manager'); // Kolom dapat null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->dropColumn('alasan_ditolak');
        });
    }
}
