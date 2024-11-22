<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginTokens extends Model
{
    use HasFactory;
    protected $fillable = [
        'device_id',
        'token',
        'user_id',
        'expires_at',
    ];
}
