<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;
    protected $table = 'anggota';

    protected $fillable = [
        'nama',
        'alamat_domisili',
        'tempat_lahir',
        'tgl_lahir',
        'alamat_ktp',
        'nik',
        'email_kantor',
        'no_handphone',
        'password',
        'status_manager',
        'status_ketua',
        'status',
        'alasan_ditolak', // Kolom baru
    ];


    public $timestamps = true; // Pastikan timestamps diaktifkan

    public function simpananPokok()
    {
        return $this->hasOne(SimpananPokok::class, 'anggota_id');
    }
    public function simpananWajib()
    {
        return $this->hasMany(SimpananWajib::class, 'anggota_id');
    }
    public function users()
    {
        return $this->hasOne(User::class);
    }
}
