<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'seller_id',
        'product_id',
        'name',
        'image',
        'unit_type',
        'price',
        'quantity',
        'total',
        'weight',
        'tax',
        'discount',
    ];
}
