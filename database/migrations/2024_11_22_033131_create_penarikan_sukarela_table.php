<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenarikanSukarelaTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('penarikan_sukarela', function (Blueprint $table) {
        $table->id();
        $table->string('no_penarikan')->unique(); // Nomor simpanan unik
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relasi ke tabel users
        $table->string('bank'); // Nama bank
        $table->bigInteger('nominal'); // Nominal penarikan
        $table->string('status_manager')->default('pending'); // Status manager: pending, approved, rejected
        $table->string('status_ketua')->default('pending'); // Status ketua: pending, approved, rejected
        $table->string('otp_code'); // Kolom OTP wajib diisi
        $table->timestamp('otp_expired_at'); // Waktu expired wajib diisi
        $table->timestamps(); // created_at, updated_at
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penarikan_sukarela');
    }
}
