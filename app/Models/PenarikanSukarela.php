<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenarikanSukarela extends Model
{
    use HasFactory;

    protected $table = 'penarikan_sukarela'; // Nama tabel di database

    protected $fillable = [
        'no_penarikan',
        'user_id',
        'bank',
        'nominal',
        'status_manager',
        'status_ketua',
        'otp_code',
        'otp_expired_at',
    ];

    /**
     * Relasi ke tabel Users.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
