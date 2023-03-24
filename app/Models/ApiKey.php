<?php

namespace App\Models;

use App\Classes\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Merchants;

class ApiKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'user_id', 'key', 'key_name', 'enabled'
    ];

    public static function generateKey($bytes=128)
    {
        return Helpers::guidv4(openssl_random_pseudo_bytes($bytes));
    }

    public function merchants()
    {
        return $this->hasMany(Merchants::class);
    }
}
