<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Routes extends Model
{
    use HasFactory;
    use \Awobaz\Compoships\Compoships;
    protected $fillable = [
        'route_id',
        'parent_id',
        'route_name',
        'is_assigned',
        'transporter_id',
        'is_driver',
        'driver_id',
        'trash',
        'deleted',
    ];
    protected $hidden = [
        'deleted',
    ];
    public function dairies()
    {
        return $this->hasMany(RoutesDairyList::class,['route_id','parent_id'],  ['route_id','parent_id']);
    }
    public function transporter()
    {
        return $this->hasOne(Transporters::class,['transporter_id','parent_id'],  ['transporter_id','parent_id']);
    }
    public function driver()
    {
        return $this->hasOne(TransportDrivers::class,'driver_id','driver_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($route) {
            $lastUser = static::latest('route_id')->first();
            if ($lastUser) {
                $lastNumber = (int)substr($lastUser->route_id, strlen('RT_'));
                $code = $lastNumber + 1;
            } else {
                $code = 001;
            }
            $routeId = 'RT_' . str_pad($code, 3, '0', STR_PAD_LEFT);
            $route->route_id = $routeId;
        });
    }
}
