<?php

namespace App\Http\Controllers\Api\v1;

use Carbon\Carbon;
use App\Models\User;
use App\Helper\Helper;
use App\Models\Pakeage;
use App\Models\LoginTokens;
use App\Models\UserProfile;
use App\Models\UserSettings;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Http\Middleware\IsProfileCompleteMiddleware;
use App\Models\MessagesAlert;

class UserController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware(IsProfileCompleteMiddleware::class, only: ['onboard']),
        ];
    }


    public function profile(Request $request)
    {
        $profile = $request->user()->load('profile');
        $profile->costumers = $profile->costumers();
        return Helper::SuccessReturn($profile, 'PROFILE_FETCHED');
    }

    public function onboard(Request $request)
    {
        $user = $request->user();
        $rules = [
            "father_name" => ['required', 'string'],
            "dairy_name" => ['required', 'string'],
            "address" => ['required', 'regex:/((\d{1,2}\/\d{1,2} [A-Za-z0-9\s]+)?|([A-Za-z0-9\s]+)?)(, [A-Za-z]+)?(, [A-Z]{2})?(, \d{5,6})?$/'],
            "latitude" => ['nullable', 'regex:/^(-?\d+(\.\d+)?)$/'],
            "longitude" => ['nullable', 'regex:/^(-?\d+(\.\d+)?)$/']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }

        // $user = auth()->user();
        $user->father_name = $request->father_name;
        $profile = new UserProfile();
        $profile->user_id = $user->user_id;
        $profile->dairy_name = $request->dairy_name;
        $profile->address = $request->address;
        $profile->latitude = $request->input('latitude', null);
        $profile->longitude = $request->input('longitude', null);
        $profile->save();
        if ($user->role == 0) {
            $plan = Pakeage::where('plan_id', 'PLAN_001')->first();
            $user->plan_id = $plan->plan_id;
            $user->plan_created = Carbon::now();
            if ($plan->duration_type == 'year') {
                $user->plan_expired  = Carbon::now()->addYears($plan->duration);
            } else if ($plan->duration_type == 'month') {
                $user->plan_expired  =  Carbon::now()->addMonths($plan->duration);
            } else {
                $user->plan_expired  =   Carbon::now()->addDays($plan->duration);
            }
        }
        $user->update();
        UserSettings::updateOrCreate([
            'user_id' => $user->user_id
        ], [
            'user_id' => $user->user_id
        ]);
        $data = $user->load('profile');
        $data->costumers = $data->costumers();
        return Helper::SuccessReturn($data, 'ACCOUNT_PROFILE_COMPLETE');
    }
    public function profileUpdate(Request $request)
    {
        $user = User::where('user_id', $request->user()->user_id)->with('profile')->first();
        $rules = [
            'name' => ['nullable', 'string', 'max:100'],
            'father_name' => ['nullable', 'string', 'max:100'],
            'dairy_name' => ['nullable', 'string'],
            'mobile' => [
                'nullable',
                'numeric',
                Rule::when(function () {
                    return in_array(request()->input('country_code'), ['91', '+91']);
                }, 'regex:/^[6789]\d{9}$/'),
                Rule::unique('users', 'mobile')->ignore($user->id)
            ],
            'country_code' => ['required_with:mobile'],
            'email' => ['nullable', 'email'],
            'image' => ['nullable', File::types(['png', 'jpg'])->min(100)
                ->max(2 * 1024)],
            'address' => ['nullable', 'string'],
            'latitude' => ['nullable'],
            'longitude' => ['nullable'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $user = User::where('user_id', $request->user()->user_id)->first();
        if (isset($request->name)) {
            $user->name = $request->name;
        }
        $user->father_name = $request->input('father_name', $user->father_name);
        $user->name = $request->input('name', $user->name);
        $user->email = $request->input('email', $user->email);
        $user->country_code = $request->input('country_code', $user->country_code);
        $user->mobile = $request->input('mobile', $user->mobile);
        $user->profile->dairy_name = $request->input('dairy_name', $user->profile->dairy_name);
        $user->profile->address = $request->input('address', $user->profile->address);
        $user->profile->latitude = $request->input('latitude', $user->profile->latitude);
        $user->profile->longitude = $request->input('longitude', $user->profile->longitude);
        if ($request->hasFile('image')) {
            $imgname = Carbon::now()->timestamp . $user->id . '.' . $request->image->extension();
            $path = storage_path('app/public/' . $user->user_id . '/profile');
            $request->image->move($path, $imgname);
            $image = ImageManager::imagick()->read($path . '/' . $imgname);
            $image = $image->resizeDown(400, 400);
            $image->save();
            $user->profile->image = $imgname;
        }
        $user->profile->update();
        $user->update();
        $profile = $user->load('profile');
        $profile->costumers = $profile->costumers();
        return Helper::SuccessReturn($profile, 'PROFILE_UPDATED');
    }

    public function settings(Request $request)
    {
        $user = $request->user();
        $settings = UserSettings::where('user_id', $user->user_id)->first();
        if (!$settings) {
            $settings = UserSettings::updateOrCreate([
                'user_id' => $user->user_id
            ], [
                'user_id' => $user->user_id
            ]);
            $settings = UserSettings::where('user_id', $user->user_id)->first();
        }
        return Helper::SuccessReturn($settings, 'SETTINGS_FATCHED');
    }
    public function settingUpdates(Request $request)
    {
        $user = $request->user();
        $rules = [
            "lang" => ['nullable', 'in:en,hi'],
            "print_font_size" => ['nullable', 'in:N,M,L'],
            "wight" => ['nullable', 'in:W,Q,L'],
            "print_size" => ['nullable', 'in:2,3'],
            "print_recipt" => ['nullable', 'in:0,1'],
            "print_recipt_all" => ['nullable', 'in:0,1'],
            "whatsapp_message" => ['nullable', 'in:0,1'],
            "auto_fats" => ['nullable', 'in:0,1'],
            "rate_par_kg" => ['nullable', 'in:0,1'],
            "fat_rate" => ['nullable', 'in:0,1'],
            "snf" => ['nullable', 'in:0,1'],
            "bonus" => ['nullable', 'in:0,1'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $settings = UserSettings::where('user_id', $user->user_id)->first();
        $settings->lang = (isset($request->lang))  ? $request->lang : $settings->lang;
        $settings->print_font_size = (isset($request->print_font_size))  ? $request->print_font_size : $settings->print_font_size;
        $settings->wight = (isset($request->wight))  ? $request->wight : $settings->wight;
        $settings->print_size = (isset($request->print_size))  ? $request->print_size : $settings->print_size;
        $settings->print_recipt = (isset($request->print_recipt))  ? $request->print_recipt : $settings->print_recipt;
        $settings->print_recipt_all = (isset($request->print_recipt_all))  ? $request->print_recipt_all : $settings->print_recipt_all;
        $settings->whatsapp_message = (isset($request->whatsapp_message))  ? $request->whatsapp_message : $settings->whatsapp_message;
        $settings->auto_fats = (isset($request->auto_fats))  ? $request->auto_fats : $settings->auto_fats;
        $settings->rate_par_kg = (isset($request->rate_par_kg))  ? $request->rate_par_kg : $settings->rate_par_kg;
        $settings->save();
        return Helper::SuccessReturn($settings, 'SETTINGS_UPDATED');
    }
    public function langUpdate(Request $request)
    {
        $user = $request->user();
        $rules = [
            "lang" => ['required', 'in:en,hi'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $settings = UserSettings::where('user_id', $user->user_id)->first();
        $settings->lang = $request->input('lang', $settings->lang);
        $settings->update();
        return Helper::SuccessReturn(null, 'LANG_UPADTED');
    }

    public function qrLogin(Request $request)
    {
        $rules = [
            "browser_id" => ['required', 'string'],
            'browser_name' => ['required', 'string']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $device = LoginTokens::where(['id' => $request->browser_id])->first();
        if (!$device) {
            return Helper::FalseReturn(null, 'INVALID_QR');
        }
        $user =  $request->user();
        $device->update(['user_id' => $user->user_id]);
        return Helper::SuccessReturn(null, 'QR_LOGIN');
    }

    public function notification(Request $request)
    {
        $user = $request->user();
        $data = MessagesAlert::where('user_id', $user->user_id)->get();
        return Helper::SuccessReturn($data, 'NOTIFICATION_FATCHED');
    }
}
