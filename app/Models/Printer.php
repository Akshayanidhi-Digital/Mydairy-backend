<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Printer extends Model
{
    use HasFactory;
    protected $fillable = [
        'printer_type',
        'user_id',
        'name',
        'port',
        'is_default',
        'trash'
    ];
    protected $hidden = [
        'trash'
    ];
}
