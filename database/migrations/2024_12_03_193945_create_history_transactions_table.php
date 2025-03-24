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
        Schema::create('history_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnUpdate()->restrictOnDelete();
            $table->string('nomor_pinjaman')->unique();
            $table->string('jenis_pinjaman')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->string('status')->default('success');
            $table->string('payment_proof')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_transactions');
    }
};
