<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;
    protected $fillable = [
        'country_code', 'mobile', 'account_type', 'token','created_at'
    ];
    public $timestamps = false;
}
