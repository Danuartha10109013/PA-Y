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
    Schema::table('penarikan_berjangka', function (Blueprint $table) {
        $table->string('status_bendahara')->nullable()->after('column_name'); // Ganti 'column_name' dengan nama kolom terakhir atau kolom tertentu.
    });
}

public function down()
{
    Schema::table('penarikan_berjangka', function (Blueprint $table) {
        $table->dropColumn('status_bendahara');
    });
}

};
