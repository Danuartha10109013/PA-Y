<?php

namespace App\Models;

use App\Models\User;
use App\Models\Tenor;
use App\Models\AmountBalance;
use App\Models\VirtualAccount;
use App\Models\PengajuanPinjaman;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PinjamanNonAngunan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $with = ['user', 'tenor', 'virtualAccount'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tenor(): BelongsTo
    {
        return $this->belongsTo(Tenor::class, 'tenor_id');
    }

    // Di model PinjamanEmergency
    public function virtualAccount(): BelongsTo
    {
        return $this->belongsTo(VirtualAccount::class);
    }

    public function pinjaman(): BelongsTo
    {
        return $this->belongsTo(PengajuanPinjaman::class);
    }
}
