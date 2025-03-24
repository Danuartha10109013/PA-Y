<?php

namespace App\Models;

use App\Models\PengajuanPinjaman;
use App\Models\PinjamanEmergency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tenor extends Model
{
    use HasFactory;

    // public function getRouteKeyName()
    // {
    //     return 'uuid';
    // }

    public function pinjamanEmergency(): HasMany
    {
        return $this->hasMany(PinjamanEmergency::class);
    }

    public function pengajuanPinjaman(): HasMany
    {
        return $this->hasMany(PengajuanPinjaman::class);
    }
}
