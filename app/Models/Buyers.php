<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Buyers extends Authenticatable
{
    use HasApiTokens,  HasFactory, Notifiable;
    use \Awobaz\Compoships\Compoships;
    protected $fillable = [
        'buyer_id',
        'name',
        'father_name',
        'country_code',
        'mobile',
        'email',
        'password',
        'parent_id',
        'is_fixed_rate',
        'fixed_rate_type',
        'rate',
        'fat_rate',
        'trash',
        'fcm_token',
    ];
    protected $hidden = [
        'fcm_token',
        'trash',
        'password'
    ];
    public function milkrecord(){
        return $this->hasMany(MilkBuyRecords::class, ['seller_id', 'buyer_id'], ['parent_id', 'buyer_id']);
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $lastUser = static::latest('buyer_id')->first();
            if ($lastUser) {
                $lastNumber = (int)($lastUser->buyer_id);
                $userCode = $lastNumber + 1;
            } else {
                $userCode = 001;
            }
            $user->buyer_id = $userCode;
        });
    }

}
