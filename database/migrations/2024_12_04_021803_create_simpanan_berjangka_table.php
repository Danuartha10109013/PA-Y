<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSimpananBerjangkaTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('simpanan_berjangka', function (Blueprint $table) {
            $table->id();
            $table->string('no_simpanan')->unique(); // Nomor simpanan unik
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relasi ke tabel users
            $table->foreignId('rekening_simpanan_berjangka_id')->constrained('rekening_simpanan_berjangka')->onDelete('cascade'); // Foreign key ke rekening_simpanan_berjangka
            $table->string('bank'); // Nama bank
            $table->bigInteger('nominal'); // Nominal simpanan
            $table->string('virtual_account')->nullable(); // Virtual account
            $table->timestamp('expired_at')->nullable(); // Expired payment
            $table->string('status_payment')->default('Menunggu Approve Manager'); // Status payment sebagai string
            $table->integer('jangka_waktu'); // Jangka waktu simpanan (dalam bulan)
            $table->decimal('jumlah_jasa_perbulan', 20, 8); // Jumlah jasa per bulan (angka desimal)
            $table->date('tanggal_pengajuan')->default(DB::raw('CURRENT_DATE')); // Tanggal pengajuan otomatis diisi dengan tanggal sekarang
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simpanan_berjangka');
    }
}
