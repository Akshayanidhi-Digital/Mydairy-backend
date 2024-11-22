<?php

namespace App\Models;

use App\Models\OrderItem;
use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Orders extends Model
{
    use HasFactory;
    use Compoships;
    protected $fillable = [
        'order_id',
        'buyer_id',
        'seller_id',
        'product_id',
        'payment_id',
        'quantity',
        'status',
        'payment_method',
        'price'
    ];
    public function getEncryptedIdAttribute()
    {
        return Crypt::encryptString($this->id);
    }

    protected static function booted()
    {
        static::retrieved(function ($order) {
            switch ($order->status) {
                case 0:
                    $order->status_name = 'Cancelled';
                    break;
                case 1:
                    $order->status_name = 'New';
                    break;
                case 2:
                    $order->status_name = 'Accepted';
                    break;
                case 3:
                    $order->status_name = 'Out for delivery';
                    break;
                case 4:
                    $order->status_name = 'Delivered';
                    break;
                case 5:
                    $order->status_name = 'Complete';
                    break;
                case 6:
                    $order->status_name = 'Return';
                    break;
                case 7:
                    $order->status_name = 'Rejected';
                    break;
                default:
                    $order->status_name = 'NA';
                    break;
            }
        });

        static::updating(function ($order) {
            unset($order->status_name);
        });
    }

    public function order_items()
    {
        return $this->hasOne(OrderItem::class, ['order_id', 'product_id'], ['order_id', 'product_id']);
    }
}
