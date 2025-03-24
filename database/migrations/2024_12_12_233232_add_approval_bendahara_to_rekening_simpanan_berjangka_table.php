<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApprovalBendaharaToRekeningSimpananBerjangkaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rekening_simpanan_berjangka', function (Blueprint $table) {
            $table->string('approval_bendahara')->nullable()->after('approval_manager');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rekening_simpanan_berjangka', function (Blueprint $table) {
            $table->dropColumn('approval_bendahara');
        });
    }
}
