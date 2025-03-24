<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekeningSimpananSukarela extends Model
{
    use HasFactory;

    // Nama tabel (jika berbeda dengan default)
    protected $table = 'rekening_simpanan_sukarela';

    // Kolom yang bisa diisi (mass assignable)
    protected $fillable = [
        'user_id',
        'status',
        'approval_manager',
        'approval_ketua',
        'approval_bendahara',
    ];

    /**
     * Relasi ke model User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke model SimpananSukarela.
     */
    public function simpananSukarela()
    {
        return $this->hasMany(SimpananSukarela::class, 'rekening_simpanan_sukarela_id');
    }
}
