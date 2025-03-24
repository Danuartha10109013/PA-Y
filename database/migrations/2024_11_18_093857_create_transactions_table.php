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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->foreignId('user_id')->constrained()->restrictOnUpdate()->restrictOnDelete();
            $table->foreignId('virtual_account_id')->nullable()->constrained()->restrictOnUpdate()->restrictOnDelete();
            // $table->foreignId('loan_category_id')->nullable()->constrained()->restrictOnUpdate()->restrictOnDelete();
            $table->string('invoice_number')->unique(); // Nomor invoice unik
            $table->decimal('amount', 15, 2); // Jumlah transaksi
            $table->string('bank')->nullable();  // Kolom untuk menyimpan bank
            $table->string('channel')->nullable();
            // $table->string('virtual_account_number')->nullable(); // Virtual Account, nullable jika belum tersedia
            $table->string('status')->default('pending'); // Status transaksi dengan default 'pending'
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
