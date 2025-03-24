<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyBankNullableInSimpananPokok extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('simpanan_pokok', function (Blueprint $table) {
            $table->string('bank')->nullable()->change();
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
            $table->string('bank')->nullable(false)->change();
        });
    }
}
