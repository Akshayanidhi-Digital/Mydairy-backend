<?php

namespace App\Http\Controllers\Api\v1;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Orders;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function myorder(Request $request){
        $user = $request->user();
        $data = Orders::where(['buyer_id'=>$user->user_id])->get();
        return Helper::SuccessReturn($data,'MY_ORDER_DATA');
    }
    public function costumers(Request $request){
        $user = $request->user();
        $data = Orders::where(['seller_id'=>$user->user_id])->get();
        return Helper::SuccessReturn($data,'COSTORDERS_DATA');
    }
}
