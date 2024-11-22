<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildDairyMilkRecords extends Model
{
    use HasFactory;
    protected $fillable = [
        'record_id',
        'seller_id',
        'buyer_id',
        'shift',
        'milk_type',
        'quantity',
        'fat',
        'snf',
        'clr',
        'bonus',
        'price',
        'total_price',
        'date',
        'is_accepted',
        'is_transport',
        'record_id',
        'is_pickedup',
        'is_delivered',
        'trash',
    ];
    protected $hidden = [
        "trash",
        "created_at",
        "updated_at",
    ];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($data) {
            $lastdata = static::latest('record_id')->first();
            if ($lastdata) {
                $lastNumber = (int)substr($lastdata->record_id, strlen('R_'));
                $code = $lastNumber + 1;
            } else {
                $code = 001;
            }
            $data->record_id = 'MYDAIRY_' . str_pad($code, 3, '0', STR_PAD_LEFT);
        });
    }
}
