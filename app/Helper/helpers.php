<?php

use Carbon\Carbon;
use App\Models\Buyers;
use App\Models\Cart;
use App\Models\Farmer;
use App\Models\DealerRoles;
use App\Models\Transporters;
use App\Models\UserProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;

function translate_to_base($name, $replace = [])
{
    return __('forms.' . $name, $replace, 'en');
}
function translate_to_app($name, $replace = [])
{
    return __($name, $replace);
}

function generate_ga_cookie()
{
    $client_id = rand(100000000, 999999999);
    $timestamp = time();
    $cookie_value = "GA1.1." . $client_id . "." . $timestamp;
    return $cookie_value;
}
function menuActive($routeName, $type = null, $param = null)
{
    if ($type == 5) $class = 'active active-menu';
    elseif ($type == 4) $class = 'collapsed';
    elseif ($type == 3) $class = 'show';
    elseif ($type == 2) $class = 'true';
    else $class = 'active';
    if (is_array($routeName)) {
        foreach ($routeName as $key => $value) {
            if (request()->routeIs($value)) return $class;
        }
    } elseif (request()->routeIs($routeName)) {
        if ($param) {
            $routeParam = array_values(@request()->route()->parameters ?? []);
            if (strtolower(@$routeParam[0]) == strtolower($param)) return $class;
            else return;
        }
        return $class;
    }
}
function getPaymentStatus($status)
{
    // 1=initiated,2=complete,3=cancelled
    if ($status == 2) {
        return '<div class="badge badge-success">COMPLETE</div>';
    } else if ($status == 3) {
        return '<div class="badge badge-danger">CANCELLED</div>';
    } else {
        return '<div class="badge badge-info">INITIATED</div>';
    }
}
function getPaymentPlanStatus($status, $end)
{
    if ($status == 1) {
        return '<div class="badge badge-success">Active</div>';
    } elseif ($status == 2) {
        return '<div class="badge badge-danger">Expired</div>';
    } else {
        return '<div class="badge badge-info">Inactive</div>';
    }
}
function getUserPlanStatus($user)
{
    return $user->isPlanExpired() ? '<div class="badge badge-danger">Expired</div>' : '<div class="badge badge-success">Active</div>';
}
function getUserProfileStatus($status)
{
    if ($status == 1) {
        return '<div class="badge badge-danger">BLOCKED</div>';
    } else {
        return '<div class="badge badge-success">ACTIVE</div>';
    }
}
function CostumerData($type = 'farmer', $trash = 0)
{
    if ($type == 'farmer') {
        return Farmer::where(['parent_id' => auth()->user()->user_id, 'trash' => $trash])->count();
    } elseif ($type == 'buyer') {
        return Buyers::where(['parent_id' => auth()->user()->user_id, 'trash' => $trash])->count();
    } else {
        if ($trash == 1) {
            return rand(50, 100);
        }
        return rand(00, 49);
    }
}
function getProfileImage($user, $type = 'user')
{
    if ($type == 'transporter') {
        $d = Transporters::where(['transporter_id' => $user])->first();
    } else {
        $d = UserProfile::where(['user_id' => $user])->first();
    }
    if ($d && $d->image_path) {
        return $d->image_path;
    } else {
        return 'assets/default.png';
        // return 'assets/panel/images/faces/face28.jpg';
    }
}

function appLogoUrl($name)
{
    $locale = App::getLocale();
    if ($locale == 'en') {
        return 'assets/logo/en/' . $name;
    } elseif ($locale == 'hi') {
        return 'assets/logo/hi/' . $name;
    } else {
        return 'assets/logo/en/' . $name;
    }
}
function getShiftIcon($name)
{
    if ($name == 'E') {
        return '<img style="width: 30px;border-radius: 0;height: auto;" src=' . asset('assets/icons/evening.png') . '  >';
    } else if ($name == 'D') {
        return '<img style="width: 30px;border-radius: 0;height: auto;" src=' . asset('assets/icons/day.png') . '  >';
    } else {
        return '<img style="width: 30px;border-radius: 0;height: auto;" src=' . asset('assets/icons/morning.png') . '  >';
    }
}
function get_dairy_roles()
{
    return DealerRoles::all();
}
function getRechageDiff($created)
{
    $created = Carbon::parse($created);
    $now = Carbon::now();
    return $created->diffForHumans($now);
}

function getShiftName($shift)
{
    if ($shift == 'M') {
        return 'Morning';
    } else if ($shift == 'E') {
        return 'Evening';
    } else {
        return 'Day';
    }
}
function getMilkTypeName($value)
{
    $milkTypeKey = array_search($value, MILK_TYPE);
    return $milkTypeKey !== false && isset(RATE_MILK_TYPE[$milkTypeKey]) ? RATE_MILK_TYPE[$milkTypeKey] : null;
}
function getCartCount($user_id)
{
    return Cart::where(['user_id' => $user_id])->count();
}

function getDashbordRoute()
{
    if (auth()->guard('transport')->user()) {
        return route('transport.dashboard');
    } else {
        return route('user.dashboard');
    }
}
function getUserName()
{
    if (auth()->guard('transport')->user()) {
        return auth('transport')->user()->name;
    } else {
        return auth()->user()->name;
    }
}
function orderStatus($status)
{
    switch ($status) {
        case 0:
            return '<div class="badge badge-danger">Cancelled</div>';
            break;
        case 1:
            return '<div class="badge badge-info">New</div>';
            break;
        case 2:
            return '<div class="badge badge-primary">Accepted</div>';
            break;
        case 3:
            return '<div class="badge badge-info">Out for delevery</div>';
            break;
        case 4:
            return '<div class="badge badge-danger">Cancelled</div>';
            break;
        case 5:
            return '<div class="badge badge-success">Completed</div>';
            break;
        case 6:
            return '<div class="badge badge-info">Return</div>';
            break;
        case 7:
            return '<div class="badge badge-danger">Rejected</div>';
            break;
        default:
            return '<div class="badge badge-primary">NA</div>';
            break;
    }
}
function customDate($time, $formet = 'H:i A d/m/Y')
{
    return Carbon::parse($time)->format($formet);
}
function getNotificationTitle($type)
{
    switch ($type) {
        case 2:
            return 'Milk Request Notification';
            break;
        default:
            return 'Message';
            break;
    }
}
function getNotificationTime($time)
{
    $created = Carbon::parse($time);
    $now = Carbon::now();
    if ($created->diffInDays($now) > 1) {
        return customDate($time);
    }
    return $created->diffForHumans($now);
}
