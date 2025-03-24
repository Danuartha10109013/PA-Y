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
        Schema::create('payment_histories', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->integer('amount');
            $table->string('payment_method');
            $table->string('nama');
            $table->string('email');
            $table->string('jenis_simpanan');
            $table->string('status')->default('pending'); // status awal pembayaran
            $table->string('payment_url')->nullable(); // URL untuk redirect ke payment gateway
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_histories');
    }
};
