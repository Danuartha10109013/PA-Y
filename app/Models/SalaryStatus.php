<?php

namespace App\Models;

use App\Models\User;
use App\Models\PengajuanPinjaman;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalaryStatus extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $with = ['user', 'pinjaman'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pinjaman(): BelongsTo
    {
        return $this->belongsTo(PengajuanPinjaman::class);
    }
    
}
