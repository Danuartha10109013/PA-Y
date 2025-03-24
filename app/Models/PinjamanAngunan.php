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

class PinjamanAngunan extends Model
{
    use HasFactory;

    public const ANGUNAN_OPTIONS = ['SERTIFIKAT TANAH','SERTIFIKAT RUMAH','BPKB KENDARAAN','SURAT BERHARGA LAINNYA'];

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
    public function virtualAccount()
    {
        return $this->belongsTo(VirtualAccount::class);
    }

    public function pinjaman(): BelongsTo
    {
        return $this->belongsTo(PengajuanPinjaman::class);
    }

}
