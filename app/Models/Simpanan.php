<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Simpanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'email',
        'jenis_simpanan',
        'amount',
        'payment_method',
        'status',
        'saldo',
        'keluar',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
