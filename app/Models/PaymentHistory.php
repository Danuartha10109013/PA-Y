<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    use HasFactory;

    protected $table = 'payment_histories';
    protected $fillable = ['invoice_number', 'amount', 'nama', 'email', 'payment_method', 'payment_url','jenis_simpanan', 'status'];
}
