<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('simpanans', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id'); // Relasi ke tabel users
            $table->string('nama');
            $table->string('email')->unique();
            $table->enum('jenis_simpanan', ['pokok', 'wajib', 'sukarela', 'berjangka']); // Jenis simpanan
            $table->decimal('amount', 15, 2); // Jumlah transaksi
            $table->string('payment_method'); // Metode pembayaran
            $table->enum('status', ['pending', 'success', 'failed']); // Status transaksi
            $table->decimal('saldo', 15, 2)->default(0); // Saldo terkini
            $table->decimal('keluar', 15, 2)->default(0); // Jumlah keluar

            // Foreign key ke tabel users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simpanans');
    }
};
