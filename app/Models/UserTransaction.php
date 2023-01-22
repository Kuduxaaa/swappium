<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTransaction extends Model
{
    use HasFactory;
    
    protected $fillable =[
        'user_id',
        'ticker',
        'method',
        'address',
        'uniqueId',
        'status',
        'nonce',
        'amount'
    ];
}
