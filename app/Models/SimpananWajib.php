<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimpananWajib extends Model
{
    use HasFactory;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'simpanan_wajib';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nominal',
        'metode_pembayaran',
        'tanggal_pembayaran',
        'status_pembayaran',
        'anggota_id',
        'no_simpanan_wajib',
    ];

    /**
     * Relationship with Anggota model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }
}
