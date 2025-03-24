<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoSimpananPokokToSimpananPokokTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('simpanan_pokok', function (Blueprint $table) {
            $table->string('no_simpanan_pokok', 50)->after('expired')->nullable()->comment('Nomor simpanan pokok');
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
            $table->dropColumn('no_simpanan_pokok');
        });
    }
}
