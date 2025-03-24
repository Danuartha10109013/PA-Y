<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimpananSukarela extends Model
{
    use HasFactory;

    // Nama tabel (jika berbeda dengan default)
    protected $table = 'simpanan_sukarela';

    // Kolom yang bisa diisi (mass assignable)
    protected $fillable = [
        'no_simpanan',
        'user_id',
        'rekening_simpanan_sukarela_id',
        'bank',
        'nominal',
        'virtual_account',
        'expired_at',
        'status_payment',
    ];

    // Kolom yang akan di-cast secara otomatis
    protected $casts = [
        'expired_at' => 'datetime',
    ];

    /**
     * Relasi ke model User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke model RekeningSimpananSukarela.
     */
    public function rekeningSimpananSukarela()
    {
        return $this->belongsTo(RekeningSimpananSukarela::class, 'rekening_simpanan_sukarela_id');
    }
}
