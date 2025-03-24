<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSimpananPokokTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('simpanan_pokok', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->decimal('nominal', 15, 2); // Nominal simpanan pokok
            $table->string('status_pembayaran', 50); // Status pembayaran
            $table->string('metode_pembayaran', 50); // Metode pembayaran
            $table->date('tanggal_pembayaran')->nullable(); // Tanggal pembayaran (opsional)
            $table->string('virtual_account', 20); // Virtual account untuk pembayaran
            $table->string('bank', 50); // Nama bank
            $table->timestamp('expired'); // Waktu kadaluarsa
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('simpanan_pokok');
    }
}
