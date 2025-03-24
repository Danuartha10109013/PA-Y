<?php

namespace App\Models;

use App\Models\Transaction;
use App\Models\VirtualAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoanCategory extends Model
{
    use HasFactory;

    public function virtualAccount(): HasMany
    {
        return $this->hasMany(VirtualAccount::class);
    }

    public function transaction(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
