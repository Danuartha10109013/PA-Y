<?php

namespace App\Models;

use App\Models\User;
use App\Models\PinjamanAngunan;
use App\Models\PinjamanEmergency;
use App\Models\PinjamanNonAngunan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AmountBalance extends Model
{
    protected $guarded = ['id'];

    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pinjamanEmergency(): BelongsTo
    {
        return $this->belongsTo(PinjamanEmergency::class);
    }

    public function pinjamanAngunan(): BelongsTo
    {
        return $this->belongsTo(PinjamanAngunan::class);
    }

    public function pinjamanNonAnguna(): BelongsTo
    {
        return $this->belongsTo(PinjamanNonAngunan::class);
    }
}
