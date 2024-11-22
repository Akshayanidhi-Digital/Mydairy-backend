<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportVehicle extends Model
{
    use HasFactory;
    protected $fillable = [
        'transporter_id',
        'driver_id',
        'vehicle_number',
        "unit",
        'capacity',
        "is_active",
    ];
    public function driver(){
        return $this->belongsTo(TransportDrivers::class,'driver_id','driver_id');
    }
}
