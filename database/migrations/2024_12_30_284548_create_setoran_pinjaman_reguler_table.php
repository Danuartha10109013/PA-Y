<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSetoranPinjamanRegulerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setoran_pinjaman_reguler', function (Blueprint $table) {
            $table->id();
            $table->string('no_setoran')->unique();
            $table->decimal('nominal', 15, 2);
            $table->string('status', 20);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('pinjaman_anggunan_id')->nullable();
            $table->unsignedBigInteger('pinjaman_tanpa_anggunan_id')->nullable();
            $table->timestamp('expired_at')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('pinjaman_anggunan_id')->references('id')->on('pinjaman_angunans')->onDelete('set null');
            $table->foreign('pinjaman_tanpa_anggunan_id')->references('id')->on('pinjaman_non_angunans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setoran_pinjaman_reguler');
    }
}
