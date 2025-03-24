<?php

namespace App\Models;

use App\Models\User;
use App\Models\Transaction;
use App\Models\LoanCategory;
use App\Models\PengajuanPinjaman;
use App\Models\PinjamanEmergency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VirtualAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'virtual_account_number',
        'status'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function loanCategory(): BelongsTo
    {
        return $this->belongsTo(LoanCategory::class);
    }

    public function pinjamanEmergency(): HasMany
    {
        return $this->hasMany(PinjamanEmergency::class);
    }

    public function transaction(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function pengajuanPinjaman(): HasMany
    {
        return $this->hasMany(PengajuanPinjaman::class);
    }
}
