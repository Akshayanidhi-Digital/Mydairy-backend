<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoutesDairyList extends Model
{
    use HasFactory;
    use \Awobaz\Compoships\Compoships;
    protected $fillable = [
        'route_id',
        'dairy_id',
        'parent_id',
    ];
    // protected $hidden = [
    //     'route_id',
    //     'parent_id',
    //     'created_at',
    //     'updated_at',
    //     'id'
    // ];
    public function routes()
    {
        return $this->belongsTo(Routes::class,['route_id','parent_id'],  ['route_id','parent_id']);
    }
}
