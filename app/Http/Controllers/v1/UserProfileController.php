<?php

namespace App\Http\Controllers\v1;

use Carbon\Carbon;
use App\Models\User;
use App\Helper\Helper;
use App\Models\UserSettings;
use Illuminate\Http\Request;
use App\Models\Userplanpackage;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\PackagePurchaseHistroy;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;

class UserProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $user = User::where(['user_id' => $user->user_id])->with('profile')->first();
        $title = __("constants.Profile");
        $plan_recharges = PackagePurchaseHistroy::where(['user_id' => $user->user_id])->orderby('end_date', 'desc')->with('plan')->get()->take(3);
        return view('user.profile.index', compact('title', 'user', 'plan_recharges'));
    }

    public function edit(Request $request)
    {
        $title = __('lang.:name Edit', ['name' => __('constants.Profile')]);
        $user = auth()->user()->load('profile');
        return view('user.profile.edit', compact('title', 'user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user()->load('profile');
        $request->validate([
            "name" => ['required', 'string', "max:100"],
            "father_name" => ['required', 'string', "max:100"],
            "email" => ['nullable', 'email', Rule::unique('users', 'email')->ignore($user->user_id, 'user_id')],
            'country_code' => ['nullable'],
            "mobile" => ['required', "numeric", Rule::when(function () use ($user) {
                return in_array(request()->input('country_code', $user->country_code), ['91', '+91']);
            }, 'regex:/^[6789]\d{9}$/'), Rule::unique('users', 'mobile')->ignore($user->user_id, 'user_id')],
            "dairy_name" => ['required'],
            "address" => ['required', "string"],
            // "latitude" => ['nullable', "numeric"],
            // "longitude" => ['nullable', "numeric"],
        ]);

        if ($request->hasFile('profile_image')) {
            $imgname = Carbon::now()->timestamp . $user->id . '.' . $request->profile_image->extension();
            $path = storage_path('app/public/' . $user->user_id . '/profile');
            $request->profile_image->move($path, $imgname);
            $image = ImageManager::imagick()->read($path . '/' . $imgname);
            $image = $image->resizeDown(400, 400);
            $image->save();
            $user->profile->image = $imgname;
        }
        $user->name = $request->input('name', $user->name);
        $user->father_name = $request->input('father_name', $user->father_name);
        $user->email = $request->input('email', $user->email);
        $user->country_code = $request->input('country_code', $user->country_code);
        $user->mobile = $request->input('mobile', $user->mobile);
        $user->profile->dairy_name = $request->input('dairy_name', $user->profile->dairy_name);
        $user->profile->address = $request->input('address', $user->profile->address);
        $user->profile->latitude = $request->input('latitude', $user->profile->latitude);
        $user->profile->longitude = $request->input('longitude', $user->profile->longitude);
        $user->profile->update();
        $user->update();
        return redirect()->route('user.profile.index')->with('success', __('message.PROFILE_UPDATED'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => ['required'],
            "password" => ['required', "min:6"],
            "confirm_password" => ['required', 'min:6', 'same:password'],
        ]);
        $user = auth()->user();
        if (!Hash::check($request->input('old_password'), $user->password)) {
            return redirect()->back()->with('error', __('message.OLD_PASSWORD_MISMATCH'));
        }
        $user->password = bcrypt($request->password);
        $user->update();
        return redirect()->route('user.profile.index')->with('success', __('message.PASSWORD_UPDATED'));
    }

    public function upgrade()
    {
        $user = auth()->user();
        if (!$user->is_single()) {
            return redirect()->route('user.profile.index')->with('error', __('message.INVALID_REQUEST'));
        }
        return $user;
    }
    public function settings()
    {
        $title = __("constants.Settings");
        $user = auth()->user();
        $settings = UserSettings::where(['user_id' => $user->user_id])->first();
        if (!$settings) {
            $settings = UserSettings::updateOrCreate([
                'user_id' => $user->user_id
            ], [
                'user_id' => $user->user_id
            ]);
            $settings = UserSettings::where('user_id', $user->user_id)->first();
        }
        return view('user.settings.index', compact('title', 'settings'));
    }
    public function settingsUpdate(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'language' => ['required', 'in:en,hi'],
            "print_font_size" => ['nullable', 'in:N,M,L'],
            "wight" => ['nullable', 'in:W,Q,L'],
            "print_size" => ['nullable', 'in:2,3'],
            "print_recipt" => ['nullable'],
            "print_recipt_all" => ['nullable'],
            "whatsapp_message" => ['nullable'],
            "auto_fats" => ['nullable'],
            "rate_par_kg" => ['nullable'],
            "fat_rate" => ['nullable'],
            "snf" => ['nullable'],
            "bonus" => ['nullable'],
        ]);
        $settings = UserSettings::where('user_id', $user->user_id)->first();
        $settings->lang = (isset($request->language))  ? $request->language : $settings->lang;
        $settings->print_font_size = (isset($request->print_font_size))  ? $request->print_font_size : $settings->print_font_size;
        $settings->wight = (isset($request->wight))  ? $request->wight : $settings->wight;
        $settings->print_size = (isset($request->print_size))  ? $request->print_size : $settings->print_size;
        $settings->print_recipt = (isset($request->print_recipt))  ? 1 : 0;
        $settings->print_recipt_all = (isset($request->print_recipt_all))  ? 1 : 0;
        $settings->whatsapp_message = (isset($request->whatsapp_message))  ? 1 : 0;
        $settings->auto_fats = (isset($request->auto_fats))  ? 1 : 0;
        $settings->rate_par_kg = (isset($request->rate_par_kg))  ? 1 : 0;
        $settings->save();
        return redirect()->back()->with('success', __('message.SETTINGS_UPDATED'));
    }
    public function lang(Request $request)
    {
        $rules = [
            'lang' => ['required', 'in:hi,en'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn([], $validator->errors()->first());
        }
        $user = auth()->user();
        $settings = UserSettings::where('user_id', $user->user_id)->first();
        $settings->lang = (isset($request->lang))  ? $request->lang : $settings->lang;
        $settings->save();
        return Helper::SuccessReturn([], __('message.LANG_UPDATED'));
    }
}
