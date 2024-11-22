<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CommonController extends Controller
{
    public function logout(Request $request)
    {
        $token = $request->user();
        $request->user()->update(['fcm_token' => null]);
        $token->delete();
        return Helper::SuccessReturn(null, 'Successfully logout.');
    }
    public function razorpayKey()
    {
        return Helper::SuccessReturn(AppSetting::getRazorpayKey(), 'Key fatched');
    }
    public function countries()
    {
        $data =  DB::table('countries')->where('status', true)->select('name', 'phone_code')->get();
        return Helper::SuccessReturn($data, 'Countries list fatched');
    }
    public function states(Request $request)
    {
        $rules = [
            'country_id' => ['required', 'numeric', Rule::exists('countries', 'id')],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn([], $validator->errors()->first());
        }
        $data = DB::table('states')
            ->where(['country_id' => $request->country_id, 'status' => true, 'type' => 'state'])
            ->get(['id as state_id', 'name', 'state_code']);
        return Helper::SuccessReturn($data, 'States list fatched');
    }
    public function cities(Request $request)
    {
        $rules = [
            'state_id' => ['required', 'numeric', Rule::exists('states', 'id')],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn([], $validator->errors()->first());
        }
        $data = DB::table('cities')
            ->where(['state_id' => $request->state_id, 'status' => true])
            ->get(['id as city_id', 'name']);
        return Helper::SuccessReturn($data, 'States list fatched');
    }
}
