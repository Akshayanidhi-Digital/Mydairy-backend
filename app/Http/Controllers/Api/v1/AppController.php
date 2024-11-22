<?php

namespace App\Http\Controllers\Api\v1;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\AppHelp;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function help()
    {
        $data = AppHelp::where('trash', false)->select('name', 'image', 'url')->get();
        return Helper::SuccessReturn($data, 'DATA_FETCHED');
    }
}
