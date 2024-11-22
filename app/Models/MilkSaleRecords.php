<?php

namespace App\Models;

use Carbon\Carbon;
use Awobaz\Compoships\Compoships;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MilkSaleRecords extends Model
{
    use HasFactory, Compoships;
    protected $fillable = [
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
        'record_type',
        'name',
        'country_code',
        'mobile',
        'trash',
        'is_accepted',
        'is_deleted',
    ];
    protected $hidden = [
        'is_deleted',
        "trash",
        "created_at",
        "updated_at",
    ];

    public function seller()
    {
        return $this->hasOne(Farmer::class, ['farmer_id', 'parent_id'], ['seller_id', 'buyer_id']);
    }
    public function buyer()
    {
        return $this->hasOne(Buyers::class, ['buyer_id', 'parent_id'], ['buyer_id', 'seller_id']);
    }
    public function user()
    {
        return $this->hasOne(User::class, 'user_id', 'buyer_id');
    }
    public function scopeWithCostumer($query)
    {
        return $query->where(function ($query) {
            $query->where(function ($subQuery) {
                $subQuery->where('record_type', 0)
                    ->whereExists(function ($existsQuery) {
                        $existsQuery->select(DB::raw(1))
                            ->from('buyers')
                            ->whereColumn('buyers.buyer_id', 'milk_sale_records.buyer_id');
                    });
            })
            ->orWhere(function ($subQuery) {
                $subQuery->where('record_type', 1)
                    ->whereExists(function ($existsQuery) {
                        $existsQuery->select(DB::raw(1))
                            ->from('users')
                            ->whereColumn('users.user_id', 'milk_sale_records.buyer_id');
                    });
            })
            ->orWhere('record_type', 2);
        });
    }

    protected static function booted()
    {
        static::retrieved(function ($data) {
            $data->time = Carbon::parse($data->created_at)->timezone(env('DEFAULT_TIMEZONE'))->format('h:i A');
            if ($data->record_type == 2) {
                $data->costumer = '';
            } else if ($data->record_type == 1) {
                $costumer = User::where(['user_id' => $data->buyer_id])->first();
                $data->costumer = $costumer ?: null;
            } else {
                $costumer = Buyers::where(['buyer_id' => $data->buyer_id])->first();
                $data->costumer = $costumer ?: null;
            }
        });
        static::updating(function ($data) {
            unset($data->time);
            unset($data->costumer);
        });
    }
}
