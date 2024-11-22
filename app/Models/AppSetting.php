<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'value',
    ];
    public static function getRazorpayKey()
    {
        return self::where('name', 'RAZORPAY_KEY')->value('value');
    }
    public static function getRazorpaySecret()
    {
        return self::where('name', 'RAZORPAY_SECRET')->value('value');
    }
    public static function updateRazorpayKey($key)
    {
        return self::updateSetting('RAZORPAY_KEY', $key);
    }
    public static function updateRazorpaySecret($secret)
    {
        return self::updateSetting('RAZORPAY_SECRET', $secret);
    }

}
