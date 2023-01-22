<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWithdraw extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'currency',
        'address',
        'amount',
        'status',
        'discription',
        'transaction_id',
    ];
}
