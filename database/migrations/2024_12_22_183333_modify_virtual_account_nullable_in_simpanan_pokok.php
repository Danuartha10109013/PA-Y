<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyVirtualAccountNullableInSimpananPokok extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('simpanan_pokok', function (Blueprint $table) {
            $table->string('virtual_account')->nullable()->change();
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
            $table->string('virtual_account')->nullable(false)->change();
        });
    }
}
