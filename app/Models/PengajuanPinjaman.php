<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengajuanPinjaman extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_pinjamans';
    protected $guarded = ['id'];
    protected $with = ['user', 'tenor', 'virtualAccount', 'pembayaran'];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * Relationship to the User model.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship to the Tenor model.
     */
    public function tenor(): BelongsTo
    {
        return $this->belongsTo(Tenor::class, 'tenor_id');
    }

    /**
     * Relationship to the VirtualAccount model.
     */
    public function virtualAccount(): BelongsTo
    {
        return $this->belongsTo(VirtualAccount::class, 'virtual_account_id');
    }

    /**
     * Relationship to the PinjamanEmergency model.
     */
    public function pinjamanEmergency(): HasMany
    {
        return $this->hasMany(PinjamanEmergency::class);
    }

    /**
     * Relationship to the PinjamanAngunan model.
     */
    public function pinjamanAngunan(): HasMany
    {
        return $this->hasMany(PinjamanAngunan::class);
    }

    /**
     * Relationship to the PinjamanNonAngunan model.
     */
    public function pinjamanNonAngunan(): HasMany
    {
        return $this->hasMany(PinjamanNonAngunan::class);
    }

    /**
     * Scope to filter records based on date range.
     */
    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when(
            $filters['start_date'] ?? false && $filters['end_date'] ?? false,
            fn($query) => $query->whereBetween('created_at', [$filters['start_date'], $filters['end_date']])
        );

        $query->when(
            $filters['start_date'] ?? false,
            fn($query, $startDate) => $query->where('created_at', '>=', $startDate)
        );

        $query->when(
            $filters['end_date'] ?? false,
            fn($query, $endDate) => $query->where('created_at', '<=', $endDate)
        );
    }

    /**
     * Relationship to the SalaryStatus model.
     */
    public function pembayaran(): HasMany
    {
        return $this->hasMany(SalaryStatus::class,'pengajuan_pinjamans_id');
    }
}
