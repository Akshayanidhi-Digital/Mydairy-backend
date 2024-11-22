<?php

namespace App\Http\Controllers\v1\Transport;

use App\Http\Controllers\Controller;
use App\Models\Routes;
use App\Models\TransportDrivers;
use App\Models\TransportVehicle;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $user = auth()->user();
        $title = __('constants.dashboard');
        $total_dairy = 1;
        $total_driver = TransportDrivers::where(['transporter_id' => $user->transporter_id,'deleted'=>false])->count();
        $total_vehicle = TransportVehicle::where(['transporter_id' => $user->transporter_id,])->count();
        $total_route = Routes::where(['transporter_id' => $user->transporter_id,'deleted' => false])->count();
        $routes_list = Routes::where(['transporter_id' => $user->transporter_id, 'trash' => false, 'deleted' => false])->with('transporter')->orderby('route_id', 'desc')->take(5)->get();
        return view('transport.Home',compact('title','routes_list','total_driver','total_vehicle','total_dairy','total_route'));
    }
}
