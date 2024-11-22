<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MilkRateChart extends Model
{
    use HasFactory;
    protected $fillable = [
        'chart_type',
        'milk_type',
        'fat',
        'snf','rate','user_id'
    ];
    protected $hidden = [
        // 'user_id'
    ];
    public function getdata(){
        return $this->belongsTo(UserProfile::class,'user_id','user_id');
    }
    protected static function booted()
    {
    }
}
