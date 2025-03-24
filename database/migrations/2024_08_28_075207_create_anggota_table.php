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
        Schema::create('anggota', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('alamat_domisili');
            $table->string('tempat_lahir');
            $table->date('tgl_lahir');
            $table->string('alamat_ktp');
            $table->string('nik');
            $table->string('email_kantor')->unique();
            $table->string('no_handphone');
            $table->string('password');
            $table->enum('status_manager', ['Pengajuan', 'Diterima', 'Ditolak'])->default('Pengajuan');
            $table->enum('status_ketua', ['Pengajuan', 'Diterima', 'Ditolak'])->default('Pengajuan');
            $table->enum('status', ['Pengajuan', 'Diterima', 'Ditolak'])->default('Pengajuan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
};
