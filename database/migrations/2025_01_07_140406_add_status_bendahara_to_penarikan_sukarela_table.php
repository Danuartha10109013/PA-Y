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
    Schema::table('penarikan_sukarela', function (Blueprint $table) {
        $table->string('status_bendahara')->default('pending')->after('kolom_sebelumnya'); // Ganti 'kolom_sebelumnya' dengan nama kolom sebelumnya
    });
}

public function down()
{
    Schema::table('penarikan_sukarela', function (Blueprint $table) {
        $table->dropColumn('status_bendahara');
    });
}

};
