<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApprovalBendaharaToAnggotaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->string('status_bendahara', 255)->default('Pengajuan')->after('status_ketua'); // Ganti 'kolom_terakhir' dengan kolom yang ada sebelum kolom baru ini
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->dropColumn('approval_bendahara');
        });
    }
}
