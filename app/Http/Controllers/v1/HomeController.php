<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $title = __('lang.Home Page');

        return view('main.home', compact('title'));
    }
    public function contact(Request $request)
    {
        $title = __('lang.Contact Page');

        return view('main.conact', compact('title'));
    }
    public function about(Request $request)
    {
        $title = __('lang.About Page');

        return view('main.about', compact('title'));
    }
    public function records()
    {
        return view('user.records.milk.index');
    }
    public function googleLoginCallback(Request $request)
    {
        return $request;
    }

    

}
