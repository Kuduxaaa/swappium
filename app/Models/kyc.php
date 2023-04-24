<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kyc extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'doc_type',
        'doc_front',
        'doc_back',
        'is_verified',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
