<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealerPermissionAllowed extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id",
        'permission_id',
        'role_id',
        'access',
    ];
    protected static function booted()
    {
        static::retrieved(function ($data) {
            $data->permission_name = DealerRolePermissions::where('permission_id',$data->permission_id)->first()->name;
        });
        static::updating(function ($data) {
            unset($data->permission_name);
        });
    }
}
