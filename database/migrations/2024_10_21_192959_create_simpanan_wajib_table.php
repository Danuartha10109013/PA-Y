<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSimpananWajibTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('simpanan_wajib', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->decimal('nominal', 15, 2); // Kolom nominal simpanan wajib
            $table->string('metode_pembayaran', 50); // Kolom metode pembayaran
            $table->date('tanggal_pembayaran'); // Kolom tanggal pembayaran
            $table->string('status_pembayaran', 20); // Kolom status pembayaran
            $table->unsignedBigInteger('anggota_id'); // Relasi ke tabel anggota
            $table->timestamps(); // Kolom created_at dan updated_at

            // Foreign key constraints
            $table->foreign('anggota_id')->references('id')->on('anggota')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('simpanan_wajib');
    }
}
