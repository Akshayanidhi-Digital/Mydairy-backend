<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    use Compoships;

    protected $fillable = [
        'order_id',
        'product_id',
        'name',
        'image',
        'unit_type',
        'price',
        'weight',
        'tax',
        'discount',
        'quantity',
        'total'
    ];

    public $timestamps = false;

    public function order()
    {
        return $this->belongsTo(Orders::class, ['order_id','product_id'], ['order_id','product_id']);
    }
}
