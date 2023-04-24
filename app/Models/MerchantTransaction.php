<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantTransaction extends Model
{
    use HasFactory;

    protected $table = 'merchant_transactions';

    protected $fillable = [
        'merchant_id',
        'user_id',
        'status',
        'amount',
        'ticker',
        'uniqueId',
        'details'
    ];
}
