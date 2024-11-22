<?php

namespace App\Http\Controllers\Api\Driver;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\MessagesAlert;
use App\Models\TransportDrivers;
use App\Models\UserOtp;
use App\Notifications\FirebaseMessage;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $rules = [
            'country_code' => ['required'],
            'mobile' => [
                'required',
                Rule::when(function () {
                    return in_array(request()->input('country_code'), ['91', '+91']);
                }, 'regex:/^[6789]\d{9}$/'),
                Rule::exists('transport_drivers', 'mobile')
            ],
            'fcm_token' => ['nullable', 'string'],
            'password' => ['required'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $user = TransportDrivers::where(['country_code' => $request->input('country_code', '+91'), 'mobile' => $request->mobile])->first();
        if (!$user) {
            return Helper::FalseReturn(null, 'USER_NOT_FOUND');
        }
        if ($user->is_blocked) {
            return Helper::FalseReturn(null, 'BLOCKED_USER');
        }
        if (!Hash::check($request->password, $user->password)) {
            return Helper::FalseReturn(null, 'PASSWORD_MISMATCH');
        }
        if ($user->fcm_token != $request->fcm_token && $user->fcm_token != null) {
            MessagesAlert::create([
                'user_id' => $user->driver_id,
                'message' => 'Your account has been accessed from a new device. If this wasn\'t you, please review your account security settings immediately',
            ]);
            FirebaseMessage::sendNotification($user->fcm_token, 'Your account has been accessed from a new device. If this wasn\'t you, please review your account security settings immediately.');
        }
        $user = Helper::FcmTokenUpdateORAdd($user, $request);
        if (!$user->is_verified) {
            $otp = 1111;
            $data = [
                'country_code' => $user->country_code,
                'user_id' => $user->mobile,
                'otp' => $otp,
                'account_type' => 5,
                'expire_at' => Carbon::now()->addMinutes(3)
            ];
            UserOtp::updateOrCreate([
                'country_code' => $user->country_code,
                'user_id' => $user->mobile,
            ], $data);
            return Helper::SuccessReturn(null, 'VERIFY_ACCOUNT_OTP', ['device' => translate_to_app('forms.mobile')]);
        }
        $user->access_token = $user->createToken($user->name)->accessToken;
        return Helper::SuccessReturn($user, 'LOGIN_SUCCESS');
    }

    public function verify(Request $request)
    {
        $rules = [
            'country_code' => ['required'],
            'mobile' => [
                'required',
                'numeric',
                Rule::when(function () {
                    return in_array(request()->input('country_code'), ['91', '+91']);
                }, 'regex:/^[6789]\d{9}$/'),
                Rule::exists('transport_drivers', 'mobile')
            ],
            'otp' => ['required', 'numeric'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $otp = UserOtp::where(['user_id' => $request->mobile, 'country_code' => $request->input('country_code', '+91'), 'account_type' => 5])->first();
        if (!$otp) {
            return Helper::FalseReturn(null, 'USER_NOT_FOUND');
        }
        if ($otp->otp != $request->otp) {
            return Helper::FalseReturn(null, 'INVALID_OTP');
        }
        if (Carbon::parse($otp->expire_at)->timestamp < Carbon::now()->timestamp) {
            return Helper::FalseReturn(null, 'EXPIRED_OTP');
        }
        $user = TransportDrivers::where(['country_code' => $request->input('country_code', '+91'), 'mobile' => $request->mobile])->first();
        $user->update(['is_verified' => 1]);
        $user->access_token = $user->createToken($user->name)->accessToken;

        return Helper::SuccessReturn($user, 'LOGIN_SUCCESS');
    }
    public function resendOtp(Request $request)
    {
        $rules = [
            'country_code' => ['required'],
            'mobile' => [
                'required',
                'numeric',
                Rule::when(function () {
                    return in_array(request()->input('country_code'), ['91', '+91']);
                }, 'regex:/^[6789]\d{9}$/'),
                Rule::exists('transport_drivers', 'mobile')
            ],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $otp = UserOtp::where(['user_id' => $request->mobile, 'country_code' => $request->input('country_code', '+91'), 'account_type' => 5])->first();
        $user = TransportDrivers::where(['country_code' => $request->input('country_code', '+91'), 'mobile' => $request->mobile])->first();
        if ($otp) {
            if (Carbon::parse($otp->expire_at)->timestamp > Carbon::now()->timestamp) {
                $diff = Carbon::parse($otp->expire_at)->diffForHumans(['parts' => 2, 'syntax' => CarbonInterface::DIFF_ABSOLUTE]);
                return Helper::EmptyReturn('RESEND_TRY_AFTER', ['time' => $diff]);
            }
        }
        $otp = 1111; // rand(1000,9999);
        $data = [
            'country_code' => $user->country_code,
            'user_id' => $user->mobile,
            'otp' => $otp,
            'account_type' => 5,
            'expire_at' => Carbon::now()->addMinutes(3)
        ];
        UserOtp::updateOrCreate([
            'country_code' => $user->country_code,
            'user_id' => $user->mobile,
        ], $data);
        return Helper::SuccessReturn(null, 'OTP_RESENT_SUCCESS', ["device" => "mobile"]);
    }
    public function forgotPassword(Request $request)
    {
        $rules = [
            'country_code' => ['required'],
            'mobile' => [
                'required',
                'numeric',
                Rule::when(function () {
                    return in_array(request()->input('country_code'), ['91', '+91']);
                }, 'regex:/^[6789]\d{9}$/'),
                Rule::exists('transport_drivers', 'mobile')
            ],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $user = TransportDrivers::where(['country_code' => $request->input('country_code', '+91'), 'mobile' => $request->mobile])->first();
        $user->password = bcrypt(123456); // send password notification
        $user->update();
        return Helper::SuccessReturn(null, 'PASSWORD_RESET_SUCCESS', ["device" => "mobile"]);
    }
}
