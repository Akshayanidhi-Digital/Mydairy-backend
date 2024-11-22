<?php

namespace App\Http\Controllers\v1\Transport;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Transporters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'mobile' => ['required', Rule::exists('transporters', 'mobile')],
                'password' => ['required'],
            ]);
            $user = Transporters::where(['mobile' => $request->mobile,])->first();
            if ($user->is_blocked) {
                return redirect()->back()->with('error' , __('message.BLOCKED_USER'))->withInput(['mobile' => $request->input('mobile', $user->mobile)]);
            } else {
                if (Auth::guard('transport')->attempt(['mobile' => $request->mobile, 'password' => $request->password])) {
                    return  redirect()->route('transport.dashboard');
                } else {
                    return redirect()->back()->with('error', __('message.USER_ACCOUNT_NOT_FOUND'))->withInput(['mobile' => $request->input('mobile', $user->mobile)]);
                }
            }
        } else {
            return view('transport.auth.login');
        }
    }
    public function forgotPassword(Request $request){
        $rules = [
            'country_code' => ['required'],
            'mobile' => [
                'required', 'numeric', Rule::when(function () {
                    return in_array(request()->input('country_code'), ['91', '+91']);
                }, 'regex:/^[6789]\d{9}$/'),
                Rule::exists('transporters', 'mobile')
            ],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $user = Transporters::where(['country_code' => $request->input('country_code', '+91'), 'mobile' => $request->mobile])->first();
        $user->password = bcrypt(123456); // send password notification
        $user->update();
        return Helper::SuccessReturn(null, 'PASSWORD_RESET_SUCCESS', ["device" => "mobile"]);
    }
    public function logout()
    {
        Auth::guard('transport')->logout();
        return redirect()->route('transport.dashboard');
    }
}
