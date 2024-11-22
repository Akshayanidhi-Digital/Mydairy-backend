<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pakeage extends Model
{
    use HasFactory;
    protected $fillable = [
        'plan_id',
        'name',
        'price',
        'duration',
        'duration_type',
        'description',
        'status',
        'category',
        'user_count',
        'farmer_count',
        'module_access',
    ];


    protected static function boot()
    {
        parent::boot();
        static::creating(function ($pack) {
            $lastPack = static::latest('plan_id')->first();
            if ($lastPack) {
                $lastNumber = (int)substr($lastPack->plan_id, strlen('PLAN_'));
                $plancode = $lastNumber + 1;
            } else {
                $plancode = 001;
            }
            $plan = 'PLAN_' . str_pad($plancode, 3, '0', STR_PAD_LEFT);
            $pack->plan_id = $plan;
        });
    }
}
