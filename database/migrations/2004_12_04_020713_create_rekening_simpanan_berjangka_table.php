<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRekeningSimpananBerjangkaTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('rekening_simpanan_berjangka', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relasi ke tabel users
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Status utama rekening
            $table->enum('approval_manager', ['pending', 'approved', 'rejected'])->default('pending'); // Approval Manager
            $table->enum('approval_ketua', ['pending', 'approved', 'rejected'])->default('pending'); // Approval Ketua
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('rekening_simpanan_berjangka');
    }
}
