<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MilkTransportRecords extends Model
{
    use HasFactory;
    protected $fillable = [
        'record_id',
        'transporter_id',
        'route_id',
        'is_transport',
        'pickedup',
        'delivered'
    ];
}
