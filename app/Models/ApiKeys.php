<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiKeys extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'user_id', 'key', 'key_name', 'enabled'
    ];
}
