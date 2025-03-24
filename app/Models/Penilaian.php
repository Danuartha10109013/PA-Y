<?php

namespace App\Models;

use App\Models\User;
use App\Models\Kriteria;
use App\Models\PengajuanPinjaman;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penilaian extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'pengajuan_pinjamans_id',
        'score',
        'level',
    ];
    

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id'); // Pastikan nama kolom foreign key benar
    }
    

    public function pinjamans(): BelongsTo
    {
        return $this->belongsTo(PengajuanPinjaman::class, 'pengajuan_pinjamans_id');
    }
}
