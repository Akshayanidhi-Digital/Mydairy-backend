<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function PHPUnit\Framework\fileExists;

class UserProfile extends Model
{
    use HasFactory;
    protected $guarded = ['image_path'];

    protected $fillable = [
        'dairy_name',
        'user_id',
        'image',
        'address',
        'latitude',
        'longitude',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    protected static function booted()
    {
        static::retrieved(function ($profile) {
            if ($profile->hasAttribute('image')) {
                // $file = storage_path('app/public/'.$profile->user_id.'/profile/'. $profile->image);
                // if(file_exists($file)){
                if ($profile->image != 'default.png') {
                    $profile->image_path = 'storage/' . $profile->user_id . '/profile/' . $profile->image;
                } else {
                    $profile->image_path = 'uploads/profile/' . $profile->image;
                }
            }
        });
        static::updating(function ($profile) {
            unset($profile->image_path);
        });
    }
}
