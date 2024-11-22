<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Transporters extends Authenticatable
{

    use HasFactory, HasApiTokens, Notifiable, Compoships;

    // protected $guarded = ['transporter_id'];

    protected $fillable = [
        'transporter_id',
        'parent_id',
        'transporter_name',
        'name',
        'father_name',
        'country_code',
        'mobile',
        'email',
        'password',
        'is_verified',
        'is_blocked',
        'deleted',
        'fcm_token',
    ];
    protected $hidden = [
        'deleted',
        'password',
        'fcm_token',
    ];
    //
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($data) {
            $lastUser = static::latest('transporter_id')->first();
            if ($lastUser) {
                $lastNumber = (int)substr($lastUser->transporter_id, strlen('TP_'));
                $code = $lastNumber + 1;
            } else {
                $code = 001;
            }
            $dataId = 'TP_' . str_pad($code, 3, '0', STR_PAD_LEFT);
            $data->transporter_id = $dataId;
        });
    }
    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }
}
