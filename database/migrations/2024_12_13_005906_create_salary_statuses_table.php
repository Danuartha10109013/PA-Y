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
        Schema::create('salary_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_pinjamans_id')->constrained()->restrictOnUpdate()->restrictOnDelete();
            $table->integer('jumlah_pembayaran');
            $table->string('bukti_pembayaran')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_statuses');
    }
};
