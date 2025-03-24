<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAnggotaIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('anggota_id')->nullable()->after('id'); // Tambahkan kolom anggota_id
            $table->foreign('anggota_id') // Tambahkan foreign key
                ->references('id')->on('anggota')
                ->onDelete('cascade'); // Hapus user jika anggota terkait dihapus
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['anggota_id']); // Hapus foreign key
            $table->dropColumn('anggota_id'); // Hapus kolom anggota_id
        });
    }
}
