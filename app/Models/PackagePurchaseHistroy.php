<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackagePurchaseHistroy extends Model
{
    use HasFactory;
    protected $fillable = [
        'plan_id',
        'user_id',
        'payment_id',
        'tnx_id',
        'payment_method',
        'payment_status',
        'amount',
        'start_date',
        'end_date',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
    public function plan()
    {
        return $this->belongsTo(Pakeage::class, 'plan_id', 'plan_id');
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $lastUser = static::latest('payment_id')->first();
            if ($lastUser) {
                $lastNumber = (int)substr($lastUser->payment_id, strlen('TNX_'));
                $userCode = $lastNumber + 1;
            } else {
                $userCode = 12013;
            }
            $userId = 'TNX_' . str_pad($userCode, 3, '0', STR_PAD_LEFT);
            $user->payment_id = $userId;
        });
    }
}
