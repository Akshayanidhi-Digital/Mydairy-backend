<?php

namespace App\Models;

use App\Models\TransportVehicle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class TransportDrivers extends Authenticatable
{
    use HasFactory,HasApiTokens, Notifiable;
    protected $fillable = [
        'transporter_id',
        'driver_id',
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
        'password',
        'deleted',
        'fcm_token',
    ];
    public function vehicle(){
        return $this->belongsTo(TransportVehicle::class,'driver_id','driver_id');
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($data) {
            $lastUser = static::latest('driver_id')->first();
            if ($lastUser) {
                $lastNumber = (int)substr($lastUser->driver_id, strlen('DR_'));
                $code = $lastNumber + 1;
            } else {
                $code = 001;
            }
            $dataId = 'DR_' . str_pad($code, 3, '0', STR_PAD_LEFT);
            $data->driver_id = $dataId;
        });
    }
}
