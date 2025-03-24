<?php

namespace App\Models;

use App\Models\User;
use App\Models\PinjamanAngunan;
use App\Models\PinjamanTanpaAngunan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SetoranPinjamanReguler extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Relasi dengan model User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi dengan model PinjamanAngunan
     */
    public function pinjamanAngunan(): BelongsTo
    {
        return $this->belongsTo(PinjamanAngunan::class, 'pinjaman_anggunan_id');
    }

    /**
     * Relasi dengan model PinjamanTanpaAngunan
     */
    public function pinjamanTanpaAngunan(): BelongsTo
    {
        return $this->belongsTo(PinjamanNonAngunan::class, 'pinjaman_tanpa_anggunan_id');
    }
}
