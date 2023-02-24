<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantSettings extends Model
{
    use HasFactory;

    protected $table ='merchant_settings';

    protected $fillable = [
       'merchant_id',
       'return_link',
       'address',
       'ticker',
       'unique_slug'
    ];
}
