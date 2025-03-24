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
        Schema::create('pinjaman', function (Blueprint $table) {
            $table->id();
            $table->string('nama_anggota');
            $table->decimal('gaji', 15, 2);
            $table->decimal('jumlah_pinjaman', 15, 2);
            $table->integer('masa_pinjam');
            $table->string('riwayat_pinjaman'); // status riwayat pinjaman
            $table->decimal('nilai_preferensi', 5, 2)->nullable(); // Nilai preferensi berdasarkan SAW
            $table->string('level')->nullable(); // Level keputusan (Bagus, Cukup Bagus, Buruk)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjaman');
    }
};
