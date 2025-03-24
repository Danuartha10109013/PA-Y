<?php

namespace App\Models;

use App\Models\User;
use App\Models\LoanCategory;
use App\Models\VirtualAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'transactions';

    protected $fillable = [
        'invoice_number',
        'amount',
        'virtual_account_number',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function virtualAccount(): BelongsTo
    {
        return $this->belongsTo(VirtualAccount::class);
    }

    public function loanCategory(): BelongsTo
    {
        return $this->belongsTo(LoanCategory::class);
    }
}
