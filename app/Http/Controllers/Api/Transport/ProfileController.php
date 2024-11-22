<?php

namespace App\Http\Controllers\Api\Transport;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Transporters;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $user = Transporters::where('transporter_id', $user->transporter_id)
            ->select('transporter_id as user_id', 'transporter_name', 'name', 'father_name', 'country_code', 'mobile', 'email')
            ->first();
        return Helper::SuccessReturn($user, 'Profile fatched successfully.');
    }
    public function update(Request $request)
    {
        $user = $request->user();
        $rules = [
            'name' => ['nullable', 'string', 'max:150'],
            "father_name" => ['nullable', 'string', 'max:150'],
            "transport_name" => ['nullable', 'string', 'max:150'],
            'email' => ['nullable', 'email'],
            'country_code' => ['nullable'],
            'fcm_token' => ['string'],
            'mobile' => ['nullable', 'numeric', Rule::when(function () {
                return in_array(request()->input('country_code'), ['91', '+91']);
            }, 'regex:/^[6789]\d{9}$/'), Rule::unique('transporters', 'mobile')->ignore($user->transporter_id, 'transporter_id')],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }

        $user->name = $request->input('name', $user->name);
        $user->father_name = $request->input('father_name', $user->father_name);
        $user->transporter_name = $request->input('transport_name', $user->transporter_name);
        $user->email = $request->input('email', $user->email);
        $user->country_code = $request->input('country_code', $user->country_code);
        $user->fcm_token = $request->input('fcm_token', $user->fcm_token);
        $user->mobile = $request->input('mobile', $user->mobile);
        $user->update();
        $user = Transporters::where('transporter_id', $user->transporter_id)
            ->select('transporter_id as user_id', 'transporter_name', 'name', 'father_name', 'country_code', 'mobile', 'email')
            ->first();
        return Helper::SuccessReturn($user, 'Profile updated successfully.');
    }
    public function updatePassword(Request $request){
        $rules = [
            'password'=>['required'],
            'new_password'=>['required','min:6'],
            "confirm_new_password"=>['required','same:new_password']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $user = $request->user();
        if(!Hash::check($request->password,$user->password)){
            return Helper::FalseReturn(null,'Invalid current password.');
        }
        $user->password = bcrypt($request->new_password);
        $user->update();
        return Helper::SuccessReturn(null,'Password updated successfully.');
    }
}
