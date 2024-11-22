<?php

namespace App\Http\Controllers\v1\Transport;

use App\Http\Controllers\Controller;
use App\Models\Routes;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index(){
        $user = auth()->user();
        $routes_list = Routes::where(['transporter_id' => $user->transporter_id, 'trash' => false, 'deleted' => false])->with('transporter')->orderby('route_id', 'desc')->paginate(env('PER_PAGE_RECORDS'));
        $title = 'Routes List';
        return view('transport.routes.index',compact('title','routes_list'));
    }
}
