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
        Schema::create('pengajuan_pinjamans', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('user_id')->constrained()->restrictOnUpdate()->restrictOnDelete();
            $table->foreignId('virtual_account_id')->constrained()->restrictOnUpdate()->restrictOnDelete();
            $table->string('nomor_pinjaman')->unique();
            $table->integer('amount');
            $table->integer('jangka_waktu',);
            $table->integer('sisa_pinjaman');
            $table->integer('sisa_jangka_waktu');
            $table->integer('nominal_angsuran');
            $table->string('jenis_pinjaman');
            $table->string('jenis_angunan')->nullable();
            $table->string('image')->nullable();
            $table->enum('status_bendahara', ['Pengajuan', 'Diterima', 'Ditolak'])->default('Pengajuan');
            $table->enum('status_manager', ['Pengajuan', 'Diterima', 'Ditolak'])->default('Pengajuan');
            $table->enum('status_ketua', ['Pengajuan', 'Diterima', 'Ditolak'])->default('Pengajuan');
            $table->enum('status', ['Pengajuan', 'Diterima', 'Ditolak'])->default('Pengajuan');
            $table->enum('status_pembayaran', ['Aktif', 'Lunas'])->default('Aktif');
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_pinjamans');
    }
};
