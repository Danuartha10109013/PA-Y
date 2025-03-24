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
        Schema::table('simpanan_wajib', function (Blueprint $table) {
            $table->date('tanggal_pembayaran')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('simpanan_wajib', function (Blueprint $table) {
            $table->date('tanggal_pembayaran')->nullable(false)->change();
        });
    }
};
