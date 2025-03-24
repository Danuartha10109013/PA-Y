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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Title
            $table->string('nama_berkas')->nullable(); // Document name (Nama Berkas)
            $table->string('original_name')->nullable(); // To store the original filename
            $table->text('deskripsi')->nullable(); // Description (Deskripsi)
            $table->timestamp('upload_date')->nullable(); // Upload date (Tanggal Unggah)
            $table->string('uploaded_by'); // Uploaded by (Diinput oleh)
            $table->string('file')->nullable(); // Kolom untuk menyimpan nama atau path file
            $table->timestamps();
        });
    }




    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
