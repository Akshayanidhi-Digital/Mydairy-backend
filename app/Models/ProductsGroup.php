<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsGroup extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id','group','trash'
    ];
}
