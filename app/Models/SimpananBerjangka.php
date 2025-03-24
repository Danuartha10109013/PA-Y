<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimpananBerjangka extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'simpanan_berjangka';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'no_simpanan',                   // Nomor simpanan
        'user_id',                       // ID user yang terkait
        'rekening_simpanan_berjangka_id', // ID rekening terkait
        'bank',                          // Bank yang dipilih
        'nominal',                       // Nominal simpanan
        'virtual_account',               // Virtual account (nullable)
        'expired_at',                    // Waktu kadaluarsa pembayaran (nullable)
        'status_payment',                // Status pembayaran
        'jangka_waktu',                  // Jangka waktu simpanan (dalam bulan)
        'jumlah_jasa_perbulan',          // Jumlah jasa per bulan
        'tanggal_pengajuan',             // Tanggal pengajuan simpanan
    ];

    /**
     * Define the relationship to the user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define the relationship to the rekening simpanan berjangka.
     */
    public function rekeningSimpananBerjangka()
    {
        return $this->belongsTo(RekeningSimpananBerjangka::class, 'rekening_simpanan_berjangka_id');
    }
}
