<?php

namespace App\Http\Controllers\v1\Admin;

use App\Models\User;
use App\Helper\Helper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $datas = User::whereIn('role', [0, 2])->with('profile')->has('profile')->paginate(env('PER_PAGE_RECORDS'));
        $title = 'All Dairy User';
        return view('admin.user.index', compact('title', 'datas'));
    }
    public function status(Request $request)
    {
        $dairy = User::where(['user_id' => $request->user_id])->first();
        if (!$dairy) {
            return Helper::FalseReturn([], 'USER_NOT_FOUND');
        }
        $dairy->is_blocked = ($dairy->is_blocked) ? false : true;
        $dairy->update();
        return Helper::SuccessReturn(null, 'USER_UPDATED');
    }
}
