<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Farmer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function index(){
        $title = __('lang.Customer Management');
        $farmers = Farmer::where(['parent_id'=>auth()->user()->user_id,'trash'=>0])->orderby('farmer_id')->get()->take(10);
        return view('user.costumers.index',compact('title','farmers'));
    }
}
