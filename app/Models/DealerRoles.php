<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealerRoles extends Model
{
    use HasFactory;
    protected $fillable = [
        'role_id',
        'short_name',
        'name',
        'status'
    ];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $lastUser = static::latest('role_id')->first();
                if ($lastUser) {
                    $lastNumber = (int)substr($lastUser->role_id, strlen('MROLE_'));
                    $userCode = $lastNumber + 1;
                } else {
                    $userCode = 001;
                }
                $userId = 'MROLE_' . str_pad($userCode, 3, '0', STR_PAD_LEFT);
            $user->role_id = $userId;
        });
    }
}
