<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'brand',
        'group',
        'name',
        'desciption',
        'image',
        'unit_type',
        'price',
        'is_tax',
        'tax',
        'is_weight',
        'weight',
        'stock',
        'trash'
    ];
    public static function getImage($id)
    {
        self::where('id', $id)->value('value');
    }
    public function unitType()
    {
        return $this->belongsTo(ProductsUnitTypes::class, 'unit_type');
    }
    protected static function booted()
    {
        static::retrieved(function ($product) {
            $group = ProductsGroup::where(['user_id' => $product->user_id, 'id' => $product->group])->first();
            $product->group_name = ($group) ? $group->group : 'NA';
            $brand = ProductsBrands::where(['user_id' => $product->user_id, 'group' => $product->group, 'id' => $product->brand])->first();
            $product->brand_name = ($brand) ? $brand->brand : 'NA';
            $product->image_base = 'storage/' . $product->user_id . '/products';
        });
        static::updating(function ($product) {
            unset($product->image_base);
            unset($product->group_name);
            unset($product->brand_name);
        });
    }
}
