<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Userplanpackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'mobile',
        'plan_id',
        'massage_plan',
        'massage_plan_created',
        'massage_plan_expire_date',
        'massage_plan_limit',
        'user_count',
        'category_plan',
    ];
}
