<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Buyers;
use App\Helper\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class BuyerController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $farmers = Buyers::where(['parent_id' => $user->user_id, 'trash' => 0])->orderby('buyer_id')
            ->select(
                'buyer_id as user_id',
                'name',
                'father_name',
                'country_code',
                'mobile',
                'email',
                'parent_id',
                'is_fixed_rate',
                'fixed_rate_type',
                'rate',
                'fat_rate'
            )->get();

        return Helper::SuccessReturn($farmers, 'DATA_FETCHED');
    }
    public function add(Request $request)
    {
        $rules = [
            'name' => ['required', 'string'],
            'father_name' => ['required', 'string'],
            'country_code' => ['required'],
            'mobile' => ['required', 'numeric', Rule::when(function () {
                return in_array(request()->input('country_code'), ['91', '+91']);
            }, 'regex:/^[6789]\d{9}$/'), Rule::unique('buyers', 'mobile')],
            'email' => ['nullable',],
            'is_fixed_rate' => ['nullable', 'in:0,1'],
            'fixed_rate_type' => [Rule::requiredIf(function () {
                return request('is_fixed_rate') == 1;
            }), 'in:0,1'],            'rate' => [Rule::requiredIf(function () {
                return request('is_fixed_rate') == 1 && request('fixed_rate_type') == 0;
            })],
            'fat_rate' => [Rule::requiredIf(function () {
                return request('is_fixed_rate') == 1 && request('fixed_rate_type') == 1;
            })]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $user = $request->user();
        $buyer = new Buyers();
        $buyer->name = $request->name;
        $buyer->father_name = $request->father_name;
        $buyer->country_code = $request->country_code;
        $buyer->mobile = $request->mobile;
        $buyer->password = bcrypt(123456); // send text message for password

        if (isset($request->email)) {
            $buyer->email = $request->email;
        }
        if (isset($request->is_fixed_rate) && $request->is_fixed_rate == 1) {
            $buyer->is_fixed_rate = 1;
            $buyer->fixed_rate_type = $request->fixed_rate_type;
            if ($request->fixed_rate_type == 0) {
                $buyer->rate = $request->rate;
            } else {
                $buyer->fat_rate = $request->fat_rate;
            }
        }
        $buyer->parent_id = $user->user_id;
        $buyer->save();
        return Helper::SuccessReturn(null, 'BUYER_ADD');
    }
    public function update(Request $request)
    {
        $rules = [
            'buyer_id' => ['required', Rule::exists('buyers', 'buyer_id')],
            "name" => ['required'],
            'father_name' => ['required', 'string'],
            'country_code' => ['required'],
            'mobile' => ['required', 'numeric', Rule::when(function () {
                return in_array(request()->input('country_code'), ['91', '+91']);
            }, 'regex:/^[6789]\d{9}$/'), Rule::unique('buyers', 'mobile')->ignore($request->buyer_id, 'buyer_id')],
            'email' => ['nullable',],
            'is_fixed_rate' => ['nullable', 'in:0,1'],
            'fixed_rate_type' => [Rule::requiredIf(function () {
                return request('is_fixed_rate') == 1;
            }), 'in:0,1'],
            'rate' => [Rule::requiredIf(function () {
                return request('is_fixed_rate') == 1 && request('fixed_rate_type') == 0;
            })],
            'fat_rate' => [Rule::requiredIf(function () {
                return request('is_fixed_rate') == 1 && request('fixed_rate_type') == 1;
            })]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $user = $request->user();
        $farmer = Buyers::where(['buyer_id' => $request->buyer_id, 'parent_id' => $user->user_id])->first();
        $farmer->name = $request->name;
        $farmer->father_name = $request->father_name;
        $farmer->country_code = $request->country_code;
        $farmer->mobile = $request->mobile;
        if (isset($request->email)) {
            $farmer->email = $request->email;
        }
        if (isset($request->is_fixed_rate) && $request->is_fixed_rate == 1) {
            $farmer->is_fixed_rate = 1;
            $farmer->fixed_rate_type = $request->fixed_rate_type;
            if ($request->fixed_rate_type == 0) {
                $farmer->rate = $request->rate;
            } else {
                $farmer->fat_rate = $request->fat_rate;
            }
        }
        $farmer->save();
        return Helper::SuccessReturn(null, 'BUYER_UPDATED');
    }
    public function updateRate(Request $request)
    {
        $rules = [
            'buyer_id' => ['required', Rule::exists('buyers', 'buyer_id')],
            'is_fixed_rate' => ['nullable', 'in:0,1'],
            'fixed_rate_type' => [Rule::requiredIf(function () {
                return request('is_fixed_rate') == 1;
            }), 'in:0,1'],
            'rate' => [Rule::requiredIf(function () {
                return request('is_fixed_rate') == 1 && request('fixed_rate_type') == 0;
            })],
            'fat_rate' => [Rule::requiredIf(function () {
                return request('is_fixed_rate') == 1 && request('fixed_rate_type') == 1;
            })]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $user = $request->user();
        $farmer = Buyers::where(['buyer_id' => $request->buyer_id, 'parent_id' => $user->user_id])->first();
        if (isset($request->is_fixed_rate) && $request->is_fixed_rate == 1) {
            $farmer->is_fixed_rate = 1;
            $farmer->fixed_rate_type = $request->fixed_rate_type;
            if ($request->fixed_rate_type == 0) {
                $farmer->rate = $request->rate;
            } else {
                $farmer->fat_rate = $request->fat_rate;
            }
        } else {
            $farmer->is_fixed_rate = 0;
        }
        $farmer->save();
        return Helper::SuccessReturn(null, 'BUYER_RATE_UPDATED');
    }
    public function delete(Request $request)
    {
        $rules = [
            'buyer_id' => ['required', Rule::exists('buyers', 'buyer_id')],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $user = $request->user();
        Buyers::where(['buyer_id' => $request->buyer_id, 'parent_id' => $user->user_id, 'trash' => 0])->update(['trash' => 1]);
        return Helper::SuccessReturn(null, 'BUYER_DELETE');
    }
    public function restore(Request $request)
    {
        $user = $request->user();
        $rules = [
            'buyer_id' => ['required', Rule::exists('buyers', 'buyer_id')],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        Buyers::where(['buyer_id' => $request->buyer_id, 'parent_id' => $user->user_id, 'trash' => 1])->update(['trash' => 0]);
        return Helper::SuccessReturn(null, 'BUYER_RESTORED');
    }
}