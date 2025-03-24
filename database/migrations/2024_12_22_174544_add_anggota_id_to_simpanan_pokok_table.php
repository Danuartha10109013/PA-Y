<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAnggotaIdToSimpananPokokTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('simpanan_pokok', function (Blueprint $table) {
            $table->unsignedBigInteger('anggota_id')->nullable()->after('id')->comment('Foreign key ke tabel anggota');
            $table->foreign('anggota_id')->references('id')->on('anggota')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('simpanan_pokok', function (Blueprint $table) {
            $table->dropForeign(['anggota_id']);
            $table->dropColumn('anggota_id');
        });
    }
}
