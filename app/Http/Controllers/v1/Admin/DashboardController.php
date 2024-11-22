<?php

namespace App\Http\Controllers\v1\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Pakeage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PackagePurchaseHistroy;

class DashboardController extends Controller
{
    public $user;

    public function __construct(Request $request)
    {
        $this->user = auth()->user();
    }


    public function index()
    {
        $total_user = User::whereNot('role', 1)->count();
        $blocked_user = User::where(['is_blocked' => false])->whereNot('role', 1)->count();
        $total_plan = Pakeage::whereNot('plan_id', 'Plan_001')->count();
        $active_plan = Pakeage::whereNot('plan_id', 'Plan_001')->where('status', 'active')->count();
        $total_payment = PackagePurchaseHistroy::where('payment_status', 2)->sum('amount');
        $today_payment = PackagePurchaseHistroy::where('payment_status', 2)->whereDate('created_at', Carbon::today())->sum('amount');
        return view('admin.home', compact('total_user', 'total_plan', 'blocked_user', 'active_plan', 'total_payment', 'today_payment'));
    }
}
