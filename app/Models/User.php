<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Anggota;
use App\Models\Pinjaman;
use App\Models\Simpanan;
use App\Models\Penilaian;
use App\Models\AmountBalance;
use App\Models\SimpananWajib;
use App\Models\VirtualAccount;
use App\Models\SimpananSukarela;
use App\Models\PengajuanPinjaman;
use Laravel\Sanctum\HasApiTokens;
use App\Models\HistoryTransaction;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'roles',
        'anggota_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function hasRole($roles)
    {
        return $this->roles === $roles;
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    // pinjaman
    public function pinjamanEmergency(): HasMany
    {
        return $this->hasMany(PinjamanEmergency::class);
    }
    public function pinjamanRegular(): HasMany
    {
        return $this->hasMany(PinjamanRegular::class, 'user_id');
    }
    public function pinjamanAngunan(): HasMany
    {
        return $this->hasMany(PinjamanAngunan::class, 'user_id');
    }
    public function pinjamanNonAngunan(): HasMany
    {
        return $this->hasMany(PinjamanNonAngunan::class, 'user_id');
    }

    public function simpananSukarela(): HasMany
    {
        return $this->hasMany(SimpananSukarela::class, 'user_id');
    }

    public function virtualAccount(): HasMany
    {
        return $this->hasMany(VirtualAccount::class,);
    }

    public function simpanan(): HasMany
    {
        return $this->hasMany(Simpanan::class, 'user_id');
    }


    public function pengajuanPinjaman(): HasMany
    {
        return $this->hasMany(PengajuanPinjaman::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(HistoryTransaction::class);
    }

    public function penilaian(): HasMany
    {
        return $this->hasMany(Penilaian::class);
    }
}
