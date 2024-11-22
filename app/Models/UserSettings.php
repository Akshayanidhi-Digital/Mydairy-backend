<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSettings extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'lang',
        'print_font_size',
        'wight',
        'print_size',
        'print_recipt',
        'print_recipt_all',
        'whatsapp_message',
        'auto_fats',
        'rate_par_kg',
        'fat_rate',
        'snf',
        'bonus',
    ];
    public static function getPrintSize($user_id)
    {
        return self::where('user_id', $user_id)->value('print_size');
    }

}
