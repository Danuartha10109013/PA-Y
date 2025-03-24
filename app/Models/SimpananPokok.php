<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class SimpananPokok extends Model
{

    use HasFactory;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'simpanan_pokok';
    protected $primaryKey = 'id'; // Pastikan primary key sesuai dengan tabel
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nominal',
        'status_pembayaran',
        'metode_pembayaran',
        'tanggal_pembayaran',
        'virtual_account',
        'bank',
        'expired',
        'anggota_id',
        'no_simpanan_pokok',
    ];


    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    /**
     * Relationship with another model, if applicable (example).
     * Uncomment and adjust as needed:
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    // public function anggota()
    // {
    //     return $this->belongsTo(Anggota::class);
    // }
}
