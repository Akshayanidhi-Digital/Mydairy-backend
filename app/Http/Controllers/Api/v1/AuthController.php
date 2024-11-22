<?php

namespace App\Http\Controllers\Api\v1;

use App\Exceptions\PublicException;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Buyers;
use App\Models\Farmer;
use App\Models\MessagesAlert;
use App\Models\PasswordReset;
use App\Models\User;
use App\Models\UserOtp;
use App\Models\UserProfile;
use App\Notifications\FirebaseMessage;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        return 'hello';
        $rules = [
            'country_code' => ['required'],
            'mobile' => [
                'required',
                'numeric',
                Rule::when(function () {
                    return in_array(request()->input('country_code'), ['91', '+91']);
                }, 'regex:/^[6789]\d{9}$/'),
                function ($attribute, $value, $fail) {
                    $accountType = request()->input('account_type');
                    if (isset($accountType) &&  $accountType == 0) {
                        $existsRule = Rule::exists('users', 'mobile')->where('role', 2);
                    } elseif (isset($accountType) && $accountType == 2) {
                        $existsRule = Rule::exists('farmers', 'mobile');
                    } elseif (isset($accountType) && $accountType == 3) {
                        $existsRule = Rule::exists('buyers', 'mobile');
                    } else {
                        $existsRule = Rule::exists('users', 'mobile')->where('role', 0);
                    }
                    if (!Validator::make([$attribute => $value], [$attribute => $existsRule])->passes()) {
                        $fail("The selected $attribute does not exist in the specified account type.");
                    }
                },
            ],
            'fcm_token' => ['nullable', 'string'],
            'account_type' => ['nullable', 'numeric', 'in:0,1,2,3'],
            'type' => ['required', 'numeric', 'in:1,2'],
            'password' => ['nullable', Rule::requiredIf(function () use ($request) {
                return $request->type == 1;
            })],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $type = $request->input('type');
        if (isset($request->account_type) && $request->account_type == 2) {
            $farmer = Farmer::where(['country_code' => $request->input('country_code'), 'mobile' => $request->input('mobile')])->first();
            if ($farmer->trash == 1) {
                return Helper::FalseReturn(null, 'BLOCKED_USER');
            }
            if ($farmer->fcm_token != $request->fcm_token && $farmer->fcm_token != null) {
                MessagesAlert::create([
                    'user_id' => $farmer->farmer_id,
                    'message' => 'Your account has been accessed from a new device. If this wasn\'t you, please review your account security settings immediately',
                ]);
                FirebaseMessage::sendNotification($farmer->fcm_token, __('message.NEW_DEVICE_LOGIN'));
            }
            $farmer = Helper::FcmTokenUpdateORAdd($farmer, $request);
            if ($type == 2) {
                $otp = 1111; // rand(100000,999999);
                $data = [
                    'user_id' => $farmer->mobile,
                    'otp' => $otp,
                    'account_type' => 2,
                    'expire_at' => Carbon::now()->addMinutes(3)
                ];
                UserOtp::updateOrCreate([
                    'user_id' => $farmer->mobile,
                ], $data);
                return Helper::SuccessReturn(null, 'LOGIN_ACCOUNT_OTP', ['device' => translate_to_app('forms.mobile')]);
            }
            if (Hash::check($request->input('password'), $farmer->password)) {
                // foreach ($farmer->tokens as $token) {
                //     $token->delete();
                // }
                $farmer->access_token = $farmer->createToken($farmer->name)->accessToken;
                return Helper::SuccessReturn($farmer, 'LOGIN_SUCCESS');
            } else {
                return Helper::FalseReturn(null, 'PASSWORD_MISMATCH');
            }
        } else if (isset($request->account_type) && $request->account_type == 0) {
            $user = User::where(['country_code' => $request->input('country_code'), 'mobile' => $request->input('mobile'), 'role' => 2])->first();
            if ($user->is_admin()) {
                return Helper::FalseReturn(null, 'ACCESS_NOT_ALLOWED');
            }
            if ($user->is_blocked) {
                return Helper::FalseReturn(null, 'BLOCKED_USER');
            }
            if ($user->fcm_token != $request->fcm_token && $user->fcm_token != null) {
                MessagesAlert::create([
                    'user_id' => $user->user_id,
                    'message' => 'Your account has been accessed from a new device. If this wasn\'t you, please review your account security settings immediately',
                ]);
                FirebaseMessage::sendNotification($user->fcm_token, __('message.NEW_DEVICE_LOGIN'));
            }
            $user = Helper::FcmTokenUpdateORAdd($user, $request);

            if ($type == 1) {
                if ($user->is_verified == 0) {
                    $otp = 1111; // rand(100000,999999);
                    $data = [
                        'user_id' => $user->mobile,
                        'otp' => $otp,
                        'account_type' => 0,
                        'expire_at' => Carbon::now()->addMinutes(3)
                    ];
                    UserOtp::updateOrCreate([
                        'user_id' => $user->mobile,
                    ], $data);
                    return Helper::SuccessReturn(null, 'VERIFY_ACCOUNT_OTP', ['device' => translate_to_app('forms.mobile')]);
                }
                if (Hash::check($request->input('password'), $user->password)) {
                    $user->access_token = $user->createToken($user->name)->accessToken;
                    $user = $user->load('profile');
                    $user->costumers = $user->costumers();
                    return Helper::SuccessReturn($user, 'LOGIN_SUCCESS');
                } else {
                    return Helper::FalseReturn(null, 'PASSWORD_MISMATCH');
                }
            } else if ($type == 2) {
                if ($user) {
                    $otp = 1111; // rand(100000,999999);
                    $data = [
                        'user_id' => $user->mobile,
                        'otp' => $otp,
                        'account_type' => 0,
                        'expire_at' => Carbon::now()->addMinutes(3)
                    ];
                    UserOtp::updateOrCreate([
                        'user_id' => $user->mobile,
                    ], $data);
                    return Helper::SuccessReturn(null, 'LOGIN_ACCOUNT_OTP', ['device' => translate_to_app('forms.mobile')]);
                } else {
                    return Helper::FalseReturn(null, 'USER_NOT_FOUND');
                }
            } else {
                return Helper::FalseReturn(null, 'SOMETHING_WENT_WRONG');
            }
        } else if (isset($request->account_type) && $request->account_type == 3) {
            $buyer = Buyers::where(['country_code' => $request->input('country_code'), 'mobile' => $request->input('mobile')])->first();

            if ($buyer->trash == 1) {
                return Helper::FalseReturn(null, 'BLOCKED_USER');
            }
            if ($buyer->fcm_token != $request->fcm_token && $buyer->fcm_token != null) {
                MessagesAlert::create([
                    'user_id' => $buyer->buyer_id,
                    'message' => 'Your account has been accessed from a new device. If this wasn\'t you, please review your account security settings immediately',
                ]);
                FirebaseMessage::sendNotification($buyer->fcm_token, __('message.NEW_DEVICE_LOGIN'));
            }
            $buyer = Helper::FcmTokenUpdateORAdd($buyer, $request);
            if ($type == 2) {
                $otp = 1111; // rand(100000,999999);
                $data = [
                    'user_id' => $buyer->mobile,
                    'otp' => $otp,
                    'account_type' => 3,
                    'expire_at' => Carbon::now()->addMinutes(3)
                ];
                UserOtp::updateOrCreate([
                    'user_id' => $buyer->mobile,
                ], $data);
                return Helper::SuccessReturn(null, 'LOGIN_ACCOUNT_OTP', ['device' => translate_to_app('forms.mobile')]);
            }
            if (Hash::check($request->input('password'), $buyer->password)) {
                //   foreach ($buyer->tokens as $token) {
                //     $token->delete();
                // }
                $buyer->access_token = $buyer->createToken($buyer->name)->accessToken;
                return Helper::SuccessReturn($buyer, 'LOGIN_SUCCESS');
            } else {
                return Helper::FalseReturn(null, 'PASSWORD_MISMATCH');
            }
        } else {
            $user = User::where(['country_code' => $request->input('country_code'), 'mobile' => $request->input('mobile'), 'role' => 0])->first();

            if ($user->is_admin()) {
                return Helper::FalseReturn(null, 'ACCESS_NOT_ALLOWED');
            }
            if ($user->is_blocked) {
                return Helper::FalseReturn(null, 'BLOCKED_USER');
            }
            if ($user->fcm_token != $request->fcm_token && $user->fcm_token != null) {
                MessagesAlert::create([
                    'user_id' => $user->user_id,
                    'message' => 'Your account has been accessed from a new device. If this wasn\'t you, please review your account security settings immediately',
                ]);
                FirebaseMessage::sendNotification($user->fcm_token, __('message.NEW_DEVICE_LOGIN'));
            }
            $user = Helper::FcmTokenUpdateORAdd($user, $request);
            if ($type == 1) {
                if ($user->is_verified == 0) {
                    $otp = 1111; // rand(100000,999999);
                    $data = [
                        'user_id' => $user->mobile,
                        'otp' => $otp,
                        'account_type' => 1,
                        'expire_at' => Carbon::now()->addMinutes(3)
                    ];
                    UserOtp::updateOrCreate([
                        'user_id' => $user->mobile,
                    ], $data);
                    return Helper::SuccessReturn(null, 'VERIFY_ACCOUNT_OTP', ['device' => translate_to_app('forms.mobile')]);
                }
                if (Hash::check($request->input('password'), $user->password)) {
                    $user->access_token = $user->createToken($user->name)->accessToken;
                    $user = $user->load('profile');
                    $user->costumers = $user->costumers();
                    return Helper::SuccessReturn($user, 'LOGIN_SUCCESS');
                } else {
                    return Helper::FalseReturn(null, 'PASSWORD_MISMATCH');
                }
            } else if ($type == 2) {
                if ($user) {
                    $otp = 1111; // rand(100000,999999);
                    $data = [
                        'user_id' => $user->mobile,
                        'otp' => $otp,
                        'account_type' => isset($request->account_type) ? $request->account_type : 1,
                        'expire_at' => Carbon::now()->addMinutes(3)
                    ];
                    UserOtp::updateOrCreate([
                        'user_id' => $user->mobile,
                    ], $data);
                    return Helper::SuccessReturn(null, 'LOGIN_ACCOUNT_OTP', ['device' => translate_to_app('forms.mobile')]);
                } else {
                    return Helper::FalseReturn(null, 'USER_NOT_FOUND');
                }
            } else {
                return Helper::FalseReturn(null, 'SOMETHING_WENT_WRONG');
            }
        }
    }
    public function signup(Request $request)
    {
        $rules = [
            "name" => ['required', 'string', "max:100"],
            'country_code' => ['nullable'],
            "mobile" => ['required', "numeric", Rule::when(function () use ($request) {
                return in_array(request()->input('country_code', $request->country_code), ['91', '+91']);
            }, 'regex:/^[6789]\d{9}$/'), Rule::unique('users', 'mobile')],
            "password" => ['required', "min:6"],
            "confirm_password" => ['required', 'min:6', 'same:password'],
            "accept_terms" => ['required'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $user = new User();
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->password = bcrypt($request->password);
        $user->country_code = $request->country_code;
        $user->role = 0;
        $user->user_type = 0;
        $user->save();
        $otp = 1111;
        $data = [
            'country_code' => $user->country_code,
            'user_id' => $user->mobile,
            'otp' => $otp,
            'account_type' => 1,
            'expire_at' => Carbon::now()->addMinutes(3)
        ];
        UserOtp::updateOrCreate([
            'user_id' => $user->user_id,
            'otp' => $otp
        ], $data);
        return Helper::SuccessReturn(null, 'VERIFY_ACCOUNT_OTP', ['device' => translate_to_app('forms.mobile')]);
    }

    public function verifyOtp(Request $request)
    {
        $rules = [
            'mobile' => [
                'required',
                'numeric',
                Rule::when(function () {
                    return in_array(request()->input('country_code'), ['91', '+91']);
                }, 'regex:/^[6789]\d{9}$/'),
                function ($attribute, $value, $fail) {
                    $accountType = request()->input('account_type');
                    if (isset($accountType) &&  $accountType == 0) {
                        $existsRule = Rule::exists('users', 'mobile')->where('role', 2);
                    } elseif (isset($accountType) && $accountType == 2) {
                        $existsRule = Rule::exists('farmers', 'mobile');
                    } elseif (isset($accountType) && $accountType == 3) {
                        $existsRule = Rule::exists('buyers', 'mobile');
                    } else {
                        $existsRule = Rule::exists('users', 'mobile')->where('role', 0);
                    }
                    if (!Validator::make([$attribute => $value], [$attribute => $existsRule])->passes()) {
                        $fail("The selected $attribute does not exist in the specified account type.");
                    }
                },
            ],
            'account_type' => ['nullable', 'numeric', 'in:0,1,2,3'],
            'country_code' => ['required'],
            'otp' => ['required', 'numeric'],
            'type' => ['required', 'numeric', 'in:1,2,3,4']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        if ($request->type == 1) {
            $otp = UserOtp::where(['user_id' => $request->mobile, 'account_type' => isset($request->account_type) ? $request->account_type : 1])->first();
            if (!$otp) {
                return Helper::FalseReturn(null, 'USER_NOT_FOUND');
            }
            if ($otp->otp != $request->otp) {
                return Helper::FalseReturn(null, 'INVALID_OTP');
            } else {
                if (isset($request->account_type) && $request->account_type == 0) {
                    $user = User::where(['country_code' => $request->input('country_code'), 'mobile' => $request->input('mobile'), 'role' => 2])->first();
                    $user->update(['is_verified' => 1]);
                    if (Carbon::parse($otp->expire_at)->timestamp < Carbon::now()->timestamp) {
                        return Helper::FalseReturn(null, 'EXPIRED_OTP');
                    }
                    $user->access_token = $user->createToken($user->name)->accessToken;
                    $user = $user->load('profile');
                    $user->costumers = $user->costumers();
                    return Helper::SuccessReturn($user, 'USER_PROFILE_VERIFIED');
                } else if (isset($request->account_type) && $request->account_type == 2) {
                    $buyer = Farmer::where(['country_code' => $request->input('country_code'), 'mobile' => $request->input('mobile')])->first();
                    if ($buyer->trash == 1) {
                        return Helper::FalseReturn(null, 'BLOCKED_USER');
                    }
                    $buyer->access_token = $buyer->createToken($buyer->name)->accessToken;
                    return Helper::SuccessReturn($buyer, 'USER_PROFILE_VERIFIED');
                } else if (isset($request->account_type) && $request->account_type == 3) {
                    $buyer = Buyers::where(['country_code' => $request->input('country_code'), 'mobile' => $request->input('mobile')])->first();
                    if ($buyer->trash == 1) {
                        return Helper::FalseReturn(null, 'BLOCKED_USER');
                    }
                    $buyer->access_token = $buyer->createToken($buyer->name)->accessToken;
                    return Helper::SuccessReturn($buyer, 'USER_PROFILE_VERIFIED');
                } else {
                    $user = User::where(['country_code' => $request->input('country_code'), 'mobile' => $request->input('mobile'), 'role' => 0])->first();
                    $user->update(['is_verified' => 1]);
                    if (Carbon::parse($otp->expire_at)->timestamp < Carbon::now()->timestamp) {
                        return Helper::FalseReturn(null, 'EXPIRED_OTP');
                    }
                    $user->access_token = $user->createToken($user->name)->accessToken;
                    $user = $user->load('profile');
                    $user->costumers = $user->costumers();
                    return Helper::SuccessReturn($user, 'USER_PROFILE_VERIFIED');
                }
            }
        } else if ($request->type == 2) {
            $otp = UserOtp::where(['user_id' => $request->mobile, 'account_type' => isset($request->account_type) ? $request->account_type : 1])->first();
            if (!$otp) {
                return Helper::FalseReturn(null, 'USER_NOT_FOUND');
            }
            if ($otp->otp != $request->otp) {
                return Helper::FalseReturn(null, 'INVALID_OTP');
            } else {
                if (isset($request->account_type) && $request->account_type == 0) {
                    $user = User::where(['country_code' => $request->input('country_code'), 'mobile' => $request->input('mobile'), 'role' => 2])->first();
                } else if (isset($request->account_type) && $request->account_type == 2) {
                    $user = Farmer::where(['country_code' => $request->input('country_code'), 'mobile' => $request->input('mobile')])->first();
                    if ($user->trash == 1) {
                        return Helper::FalseReturn(null, 'BLOCKED_USER');
                    }
                } else if (isset($request->account_type) && $request->account_type == 3) {
                    $user = Buyers::where(['country_code' => $request->input('country_code'), 'mobile' => $request->input('mobile')])->first();
                    if ($user->trash == 1) {
                        return Helper::FalseReturn(null, 'BLOCKED_USER');
                    }
                } else {
                    $user = User::where(['country_code' => $request->input('country_code'), 'mobile' => $request->input('mobile'), 'role' => 0])->first();
                }
                $token = Str::Uuid();
                $data = [
                    'country_code' => $user->country_code,
                    'mobile' => $user->mobile,
                    'account_type' => isset($request->account_type) ? $request->account_type : 1,
                    'token' => $token,
                    'created_at' => now()
                ];
                PasswordReset::updateOrCreate($data, $data);
                return Helper::SuccessReturn(['reset_token' => $token], 'PASSWORD_RESET_OTP_SENT', ['device' => translate_to_app('forms.mobile')]);
                // for account typoe ?
            }
        } else if ($request->type == 3) {
            $otp = UserOtp::where('user_id', $request->mobile)->first();
            if (!$otp) {
                return Helper::FalseReturn(null, 'USER_NOT_FOUND');
            }
            if ($otp->otp != $request->otp) {
                return Helper::FalseReturn(null, 'INVALID_OTP');
            } else {
                if (isset($request->account_type) && $request->account_type == 0) {
                    $user = User::where(['country_code' => $request->input('country_code'), 'mobile' => $request->input('mobile'), 'role' => 2])->first();
                    $user->update(['is_verified' => 1]);
                    if (Carbon::parse($otp->expire_at)->timestamp < Carbon::now()->timestamp) {
                        return Helper::FalseReturn(null, 'EXPIRED_OTP');
                    }
                    $user->access_token = $user->createToken($user->name)->accessToken;
                    $user = $user->load('profile');
                    $user->costumers = $user->costumers();
                    return Helper::SuccessReturn($user, 'OTP_LOGIN_SUCCESS');
                } else if (isset($request->account_type) && $request->account_type == 2) {
                    $buyer = Farmer::where(['country_code' => $request->input('country_code'), 'mobile' => $request->input('mobile')])->first();
                    if ($buyer->trash == 1) {
                        return Helper::FalseReturn(null, 'BLOCKED_USER');
                    }
                    $buyer->access_token = $buyer->createToken($buyer->name)->accessToken;
                    return Helper::SuccessReturn($buyer, 'OTP_LOGIN_SUCCESS');
                } else if (isset($request->account_type) && $request->account_type == 3) {
                    $buyer = Buyers::where(['country_code' => $request->input('country_code'), 'mobile' => $request->input('mobile')])->first();
                    if ($buyer->trash == 1) {
                        return Helper::FalseReturn(null, 'BLOCKED_USER');
                    }
                    $buyer->access_token = $buyer->createToken($buyer->name)->accessToken;
                    return Helper::SuccessReturn($buyer, 'OTP_LOGIN_SUCCESS');
                } else {
                    $user = User::where(['country_code' => $request->input('country_code'), 'mobile' => $request->input('mobile'), 'role' => 0])->first();
                    $user->update(['is_verified' => 1]);
                    if (Carbon::parse($otp->expire_at)->timestamp < Carbon::now()->timestamp) {
                        return Helper::FalseReturn(null, 'EXPIRED_OTP');
                    }
                    $user->access_token = $user->createToken($user->name)->accessToken;
                    $user = $user->load('profile');
                    $user->costumers = $user->costumers();
                    return Helper::SuccessReturn($user, 'OTP_LOGIN_SUCCESS');
                }
            }
        } else {
            return Helper::FalseReturn(null, 'INVALID_ACTIVITY');
        }
    }
    public function resendOtp(Request $request)
    {
        $rules = [
            'mobile' => [
                'required',
                'numeric',
                Rule::when(function () {
                    return in_array(request()->input('country_code'), ['91', '+91']);
                }, 'regex:/^[6789]\d{9}$/'),
                function ($attribute, $value, $fail) {
                    $accountType = request()->input('account_type');
                    if (isset($accountType) &&  $accountType == 0) {
                        $existsRule = Rule::exists('users', 'mobile')->where('role', 2);
                    } elseif (isset($accountType) && $accountType == 2) {
                        $existsRule = Rule::exists('farmers', 'mobile');
                    } elseif (isset($accountType) && $accountType == 3) {
                        $existsRule = Rule::exists('buyers', 'mobile');
                    } else {
                        $existsRule = Rule::exists('users', 'mobile')->where('role', 0);
                    }
                    if (!Validator::make([$attribute => $value], [$attribute => $existsRule])->passes()) {
                        $fail("The selected $attribute does not exist in the specified account type.");
                    }
                },
            ],
            'account_type' => ['nullable', 'numeric', 'in:0,1,2,3'],
            'country_code' => ['required'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $otp = UserOtp::where('user_id', $request->mobile)->first();
        if ($otp) {
            if (Carbon::parse($otp->expire_at)->timestamp > Carbon::now()->timestamp) {
                $diff = Carbon::parse($otp->expire_at)->diffForHumans(['parts' => 2, 'syntax' => CarbonInterface::DIFF_ABSOLUTE]);
                return Helper::EmptyReturn('RESEND_TRY_AFTER', ['time' => $diff]);
            }
            $otp = 1111; // rand(100000,999999);
            $data = [
                'user_id' => $request->input('mobile'),
                'otp' => $otp,
                'account_type' => isset($request->account_type) ? $request->account_type : 1,
                'expire_at' => Carbon::now()->addMinutes(3)
            ];
            UserOtp::updateOrCreate([
                'user_id' => $request->input('mobile'),
            ], $data);
            return Helper::SuccessReturn(null, 'OTP_RESENT_SUCCESS', ["device" => "mobile"]);
        }
        $otp = 1111; // rand(100000,999999);
        $data = [
            'user_id' => $request->input('mobile'),
            'otp' => $otp,
            'account_type' => isset($request->account_type) ? $request->account_type : 1,
            'expire_at' => Carbon::now()->addMinutes(3)
        ];
        UserOtp::updateOrCreate([
            'user_id' => $request->input('mobile'),
        ], $data);
        return Helper::SuccessReturn(null, 'OTP_RESENT_SUCCESS');
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
                function ($attribute, $value, $fail) {
                    $accountType = request()->input('account_type');
                    if (isset($accountType) &&  $accountType == 0) {
                        $existsRule = Rule::exists('users', 'mobile')->where('role', 2);
                    } elseif (isset($accountType) && $accountType == 2) {
                        $existsRule = Rule::exists('farmers', 'mobile');
                    } elseif (isset($accountType) && $accountType == 3) {
                        $existsRule = Rule::exists('buyers', 'mobile');
                    } else {
                        $existsRule = Rule::exists('users', 'mobile')->where('role', 0);
                    }
                    if (!Validator::make([$attribute => $value], [$attribute => $existsRule])->passes()) {
                        $fail("The selected $attribute does not exist in the specified account type.");
                    }
                },
            ],
            'account_type' => ['nullable', 'numeric', 'in:0,1,2,3'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        if (isset($request->account_type) && $request->account_type == 0) {
            $user = User::where(['country_code' => $request->input('country_code'), 'mobile' => $request->input('mobile'), 'role' => 2])->first();
        } else if (isset($request->account_type) && $request->account_type == 2) {
            $user = Farmer::where(['country_code' => $request->input('country_code'), 'mobile' => $request->input('mobile')])->first();
            if ($user->trash == 1) {
                return Helper::FalseReturn(null, 'BLOCKED_USER');
            }
        } else if (isset($request->account_type) && $request->account_type == 3) {
            $user = Buyers::where(['country_code' => $request->input('country_code'), 'mobile' => $request->input('mobile')])->first();
            if ($user->trash == 1) {
                return Helper::FalseReturn(null, 'BLOCKED_USER');
            }
        } else {
            $user = User::where(['country_code' => $request->input('country_code'), 'mobile' => $request->input('mobile'), 'role' => 0])->first();
        }
        if (!$user) {
            return Helper::FalseReturn(null, 'USER_NOT_FOUND');
        }
        $otp = 1111; // rand(100000,999999);
        $data = [
            'user_id' => $user->mobile,
            'otp' => $otp,
            'account_type' => isset($request->account_type) ? $request->account_type : 1,
            'expire_at' => Carbon::now()->addMinutes(3)
        ];
        UserOtp::updateOrCreate([
            'user_id' => $user->mobile,
        ], $data);
        return Helper::SuccessReturn(null, 'PASSWORD_RESET_OTP_SENT', ['device' => translate_to_app('forms.mobile')]);
    }
    public function passwordUpdate(Request $request)
    {
        $rules = [
            'new_password' => ['required', 'min:6'],
            'confirm_new_password' => ['required', 'min:6', 'same:new_password'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $token = $request->input('reset_token');
        if (!$token) {
            return  Helper::StatusReturn(null, 'BAD_REQUEST', [], [], STATUS_FORBIDDEN);
        }
        $data = PasswordReset::where('token', $token)->first();
        if (!$data) {
            return  Helper::StatusReturn(null, 'UNAUTHORIZED_ACCESS_PASSWORD', [], [], STATUS_UNAUTHORIZED);
        }
        if ($data->account_type == 0) {
            $user = User::where(['mobile' => $data->mobile, 'country_code' => $data->country_code, 'role' => 2])->update([
                'password' => bcrypt($request->input('new_password'))
            ]);
        } else if ($data->account_type == 2) {
            $use = Farmer::where(['mobile' => $data->mobile, 'country_code' => $data->country_code])->update([
                'password' => bcrypt($request->input('new_password'))
            ]);
        } else if ($data->account_type == 3) {
            $use = Buyers::where(['mobile' => $data->mobile, 'country_code' => $data->country_code])->update([
                'password' => bcrypt($request->input('new_password'))
            ]);
        } else {
            $user = User::where(['mobile' => $data->mobile, 'country_code' => $data->country_code, 'role' => 0])->update([
                'password' => bcrypt($request->input('new_password'))
            ]);
        }
        $data->delete();
        return Helper::SuccessReturn(null, 'PASSWORD_RESET_SUCCESSFULLY');
    }
}
