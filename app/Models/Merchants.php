<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchants extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'api_key_id',
        'ticker',
        'network',
        'address'
    ];
}
