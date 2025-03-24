<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pinjaman_angunans', function (Blueprint $table) {
            $table->string('no_pinjaman')->unique()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pinjaman_angunans', function (Blueprint $table) {
            $table->dropColumn('no_pinjaman');
        });
    }
};
