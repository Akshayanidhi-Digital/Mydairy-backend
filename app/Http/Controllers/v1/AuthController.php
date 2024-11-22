<?php

namespace App\Http\Controllers\v1;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Pakeage;
use App\Models\UserOtp;
use Endroid\QrCode\QrCode;
use App\Models\LoginTokens;
use App\Models\UserProfile;
use Illuminate\Support\Str;
use App\Models\UserSettings;
use Illuminate\Http\Request;
use Endroid\QrCode\Color\Color;
use Illuminate\Validation\Rule;
use Endroid\QrCode\Builder\Builder;
use App\Http\Controllers\Controller;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Auth;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        if ($request->has('with') &&  $request->with == "google") {
            return Socialite::driver('google')->redirect();
        }
        if (auth()->user()) {
            return (auth()->user()->is_admin()) ? redirect()->route('admin.dashboard') : redirect()->route('user.dashboard');
        }
        if ($request->hasCookie('_ga')) {
            $browserId = $request->cookie('_ga');
        } else {
            $browserId = generate_ga_cookie();
        }
        return response()->view('user.auth.login')->cookie('_ga', $browserId, 10080);
    }
    public function qrCode(Request $request)
    {

        if ($request->hasCookie('_ga')) {
            $browserId = $request->cookie('_ga');
        } else {
            $browserId = generate_ga_cookie();
        }
        $token = Str::uuid();

        if ($browserId) {
            $token = LoginTokens::updateOrCreate([
                'device_id' => $browserId,
            ], [
                'device_id' => $browserId,
                'token' => $token,
                'user_id' => null,
                'expires_at' => Carbon::now()->addMinutes(10),
            ]);
        }
        $data = [
            'id' => $token->id,
            "string" => 'Mydairy',
        ];
        $path = public_path('assets/qr-logo.png');
        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data(json_encode($data))
            ->encoding(new Encoding('UTF-8'))
            ->foregroundColor(new Color(0, 102, 183))
            ->backgroundColor(new Color(255, 255, 255))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size(300)
            ->margin(10)
            ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->logoPath($path)
            ->logoResizeToWidth(50)
            ->logoPunchoutBackground(true)
            ->validateResult(true)
            ->build();
        header('Content-Type: ' . $result->getMimeType());
        return response($result->getString());
    }


    public function loginPost(Request $request)
    {
        $request->validate([
            'mobile' => ['required', Rule::exists('users', 'mobile')],
            'password' => ['required'],
        ]);

        $user = User::where(['mobile' => $request->mobile, 'country_code' => '+91'])->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Invalid Username and Password')->withInput(['mobile' => $request->input('mobile')]);
        }

        if ($user->is_verified == 0 && $user->role != 1) {
            return redirect()->route('verify')->with([
                'error' => __('message.USER_ACCOUNT_NOT_VERIFY'),
                'mobile' => $request->input('mobile', $user->mobile),
                'country_code' => $request->input('country_code', '+91')
            ]);
        } else {
            if (Auth::attempt(['mobile' => $request->mobile, 'password' => $request->password])) {
                return auth()->user()->is_admin() ? redirect()->route('admin.dashboard') : redirect()->route('user.dashboard');
            } else {
                return redirect()->back()->with('error', __('message.USER_ACCOUNT_NOT_FOUND'));
            }
        }
    }


    public function qrCheck(Request $request)
    {
        $browserId = $request->cookie('_ga');
        $device = LoginTokens::where(['device_id' => $browserId])->first();
        if (!$device) {
            return response()->json([
                'status' => false,
                'message' => 'Waiting for response',
            ]);
        }
        $user = User::where('user_id', "=", $device->user_id)->first();
        if ($user) {
            Auth::login($user);
            $device->update(['user_id' => null]);
            return response()->json([
                'status' => true,
                'message' => 'Login Success',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'waiting for qr scan',
            ]);
        }
    }
    public function verify()
    {
        if (session()->get('mobile') && session()->get('country_code')) {
            $user = User::where(['mobile' => session()->get('mobile')])->first();
            $otp = 1111;
            $data = [
                'user_id' => $user->mobile,
                'otp' => $otp,
                'account_type' => ($user->role == 2) ? 0 : 1,
                'expire_at' => Carbon::now()->addMinutes(3)
            ];
            UserOtp::updateOrCreate([
                'user_id' => $user->mobile,
            ], $data);
            return view('user.auth.verify');
        } else {
            return redirect()->route('login');
        }
    }
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'mobile' => ['required', Rule::exists('users', 'mobile')],
            'otp' => ['required'],
        ]);
        $user = User::where(['mobile' => $request->mobile])->first();
        $account_type = ($user->role == 2) ? 0 : 1;
        $otp = UserOtp::where(['user_id' => $request->mobile, 'account_type' => $account_type])->first();
        if (!$otp) {
            return redirect()->back()->with('error', __('message.USER_NOT_FOUND'));
        }
        if ($otp->otp != $request->otp) {
            return redirect()->back()->with('error', __('message.INVALID_OTP'));
        } else {
            if (Carbon::parse($otp->expire_at)->timestamp < Carbon::now()->timestamp) {
                return redirect()->back()->with('error', __('message.EXPIRED_OTP'));
            } else {
                $user->update(['is_verified' => 1]);
                return redirect()->route('login')->with('success', __('message.USER_VERIFIED'));
            }
        }
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
    public function register()
    {
        $title = __('Register');

        return view('user.auth.register', compact('title'));
    }
    // public function registerPost(Request $request)
    // {
    //     $request->validate([
    //         "name" => ['required', 'string', "max:100"],
    //         'country_code' => ['nullable'],
    //         "mobile" => ['required', "numeric", Rule::when(function () use ($request) {
    //             return in_array(request()->input('country_code', $request->country_code), ['91', '+91']);
    //         }, 'regex:/^[6789]\d{9}$/'), Rule::unique('users', 'mobile')],
    //         "password" => ['required', "min:6"],
    //         "confirm_password" => ['required', 'min:6', 'same:password'],
    //         "accept_terms" => ['required'],
    //     ]);
    //     $user = new User();
    //     $user->name = $request->name;
    //     $user->mobile = $request->mobile;
    //     $user->password = bcrypt($request->password);
    //     $user->country_code = $request->country_code;
    //     $user->role = 0;
    //     $user->user_type = 0;
    //     $user->save();
    //     return redirect()->route('verify')->with(['error' => __('message.VERIFY_ACCOUNT_OTP'), 'mobile' => $request->input('mobile', $user->mobile), 'country_code' => $request->input('country_code', '+91')]);
    // }




    public function registerPost(Request $request)
    {
        // Validation rules
        $request->validate([
            "name" => ['required', 'string', "max:100"],
            'country_code' => ['nullable'],
            "mobile" => ['required', "numeric", Rule::when(function () use ($request) {
                return in_array(request()->input('country_code', $request->country_code), ['91', '+91']);
            }, 'regex:/^[6789]\d{9}$/'), Rule::unique('users', 'mobile')],
            "password" => ['required', "min:6"],
            "confirm_password" => ['required', 'min:6', 'same:password'],
            "accept_terms" => ['required'],
        ]);

        // Create a new user instance
        $user = new User();
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->password = bcrypt($request->password);
        $user->country_code = $request->country_code;
        $user->role = 0;  // Default role, adjust based on your logic
        $user->user_type = 0; // Default user type, adjust as needed

        // Generate a unique user_id based on the role
        $user->user_id = $this->generateUniqueUserId($user->role);
        // return $user->user_id;
        // Save the user to the database
        $user->save();

        // Redirect to the verification page with a success message
        return redirect()->route('verify')->with([
            'error' => __('message.VERIFY_ACCOUNT_OTP'),
            'mobile' => $request->input('mobile', $user->mobile),
            'country_code' => $request->input('country_code', '+91')
        ]);
    }

    /**
     * Generate a unique user_id based on the user's role.
     *
     * @param int $role
     * @return string
     */
    private function generateUniqueUserId($role)
    {
        $prefix = '';
        switch ($role) {
            case 1:
                $prefix = 'ADMIN_';
                break;
            case 2:
                $prefix = 'MYDAIRYSUB_';
                break;
            default:
                $prefix = 'MYDAIRY_';
                break;
        }

        $lastUser = User::where('role', $role)
            ->where('user_id', 'like', $prefix . '%')
            ->orderByDesc('user_id')
            ->first();
        if ($lastUser) {
            $lastNumber = (int)substr($lastUser->user_id, strlen($prefix));
            $userCode = $lastNumber + 1;
        } else {
            $userCode = 1;
        }
        $userId = $prefix . str_pad($userCode, 3, '0', STR_PAD_LEFT);
        while (User::where('user_id', $userId)->exists()) {
            $userCode++;
            $userId = $prefix . str_pad($userCode, 3, '0', STR_PAD_LEFT);
        }

        return $userId;
    }







    public function onboard(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                "father_name" => ['required', 'string'],
                "dairy_name" => ['required', 'string'],
                "address" => ['required', 'regex:/((\d{1,2}\/\d{1,2} [A-Za-z0-9\s]+)?|([A-Za-z0-9\s]+)?)(, [A-Za-z]+)?(, [A-Z]{2})?(, \d{5,6})?$/'],
                "latitude" => ['nullable', 'regex:/^(-?\d+(\.\d+)?)$/'],
                "longitude" => ['nullable', 'regex:/^(-?\d+(\.\d+)?)$/']
            ]);
            $user = auth()->user();
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
            return redirect()->route('user.dashboard')->with('success', __('message.ACCOUNT_PROFILE_COMPLETE'));
        } else {
            $title = 'Complete Profile';
            return view('user.auth.onbord', compact('title'));
        }
    }
}
