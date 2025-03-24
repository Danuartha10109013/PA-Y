<?php

namespace App\Models;

use App\Models\User;
use App\Models\PengajuanPinjaman;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HistoryTransaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pengajuanPinjaman(): BelongsTo
    {
        return $this->belongsTo(PengajuanPinjaman::class);
    }
}
