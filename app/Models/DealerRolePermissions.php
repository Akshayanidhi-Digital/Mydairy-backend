<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealerRolePermissions extends Model
{
    use HasFactory;
    protected $fillable = [
        'permission_id',
        'name',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $lastUser = static::latest('permission_id')->first();
            if ($lastUser) {
                $lastNumber = (int)substr($lastUser->permission_id, strlen('MPER_'));
                $userCode = $lastNumber + 1;
            } else {
                $userCode = 001;
            }
            $userId = 'MPER_' . str_pad($userCode, 3, '0', STR_PAD_LEFT);
            $user->permission_id = $userId;
        });
    }
}
