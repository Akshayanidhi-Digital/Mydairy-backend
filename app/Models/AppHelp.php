<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppHelp extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'image',
        'url',
        'trash',
    ];
    protected static function booted()
    {
        static::retrieved(function ($help) {
            if ($help->hasAttribute('image')) {
                $help->image_path = 'storage/help_image/'. $help->image;
            }
        });
        static::updating(function ($help) {
            unset($help->image_path);
        });
    }
}
