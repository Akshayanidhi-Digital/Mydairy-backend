<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsBrands extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'brand',
        'group',
        'trash'
    ];
    protected static function booted()
    {
        static::retrieved(function ($brand) {
            $group = ProductsGroup::where(['user_id' => $brand->user_id, 'id' => $brand->group])->first();
            $brand->group_name = ($group) ? $group->group : 'NA';
        });
        static::updating(function ($brand) {
            unset($brand->group_name);
        });
    }
}
