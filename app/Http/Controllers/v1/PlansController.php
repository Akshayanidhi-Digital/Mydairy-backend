<?php

namespace App\Http\Controllers\v1;

use Carbon\Carbon;
use Razorpay\Api\Api;
use App\Models\Pakeage;
use App\Models\AppSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\PackagePurchaseHistroy;
use Illuminate\Support\Facades\Session;
use App\Http\Middleware\IsDairyMiddleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class PlansController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            IsDairyMiddleware::class
        ];
    }

    public function index()
    {
        $title = __('constants.Billing History');
        $user = auth()->user();
        $records = PackagePurchaseHistroy::where(['user_id' => $user->user_id])->orderby('id', 'desc')->with('plan')->paginate(env('PER_PAGE_RECORDS'));
        return view('user.plans.index', compact('title', 'records'));
    }
    public function create()
    {
        $title  = 'Add Plan';
        $plans = Pakeage::query();
        $plans = Pakeage::where('status', 'active')->whereNot('plan_id', 'Plan_001');
        if (!auth()->user()->is_single()) {
            $plans = $plans->where('category', 'multiple');
        }
        $plans = $plans->get();
        $title  = 'Add Plan';
        return view('user.plans.create', compact('title', 'plans'));
    }
    public function add($id)
    {
        $user = auth()->user();
        $plan = Pakeage::where('plan_id', $id)->first();
        $lastrecharge = PackagePurchaseHistroy::where('user_id', $user->user_id)->orderby('id', 'desc')->first();
        if ($user->planexpired()) {
            $datetime = Carbon::now();
        } else {
            if ($lastrecharge) {
                $datetime = Carbon::parse($lastrecharge->end_date);
            } else {
                $datetime = Carbon::now();
            }
        }
        $histroy = new PackagePurchaseHistroy();
        $histroy->user_id = $user->user_id;
        $histroy->plan_id = $plan->plan_id;
        $histroy->payment_method = 'Online';
        $histroy->amount = $plan->price;
        $histroy->start_date = $datetime;
        if ($plan->duration_type == 'year') {
            $histroy->end_date = Carbon::parse($datetime)->addYears($plan->duration);
        } else if ($plan->duration_type == 'month') {
            $histroy->end_date =  Carbon::parse($datetime)->addMonths($plan->duration);
        } else {
            $histroy->end_date =   Carbon::parse($datetime)->addDays($plan->duration);
        }
        $histroy->status = ($user->planexpired()) ? 1 : 0;
        $histroy->save();
        return redirect()->route('user.plans.pay', $histroy->id)->with('success', __('message.MAKE_PAYMENT_FOR_PLAN'));
    }
    public function pay($id)
    {
        $user = auth()->user();
        $plan = PackagePurchaseHistroy::where(['id' => $id, 'user_id' => $user->user_id,])->whereNot('payment_status', 2)->first();
        if (!$plan) {
            return redirect()->route('user.plans.list')->with('error', __('message.INVALID_REQUEST'));
        }
        $apikey = AppSetting::getRazorpayKey();
        $apisecrate = AppSetting::getRazorpaySecret();
        $api = new Api($apikey, $apisecrate);
        $razororder = $api->order->create([
            // 'amount' => $plan->amount * 100,
            'amount' =>  100,
            'currency' => 'INR',
            'payment_capture' => 1,
        ]);
        $plan->tnx_id = $razororder->id;
        $plan->save();
        Session::put('payment_data', [
            'order_id' => $razororder->id,
            'user_uni_id' => auth()->user()->user_uni_id,
            'amount' => $plan->amount,
        ]);
        $data = [
            'order_id' => $razororder->id,
            'razorpay_key' => $apikey,
            // 'amount' => $plan->amount * 100,
            'amount' => 100,
            'currency' => 'INR',
            'plan' => $plan,
        ];
        return view('payment.razorpay.planpay', compact('data'));
    }
    public function paymentStatus($id)
    {
        $user = auth()->user();
        $sessionpay = session()->get('payment_data');
        $apikey = AppSetting::getRazorpayKey();
        $apisecrate = AppSetting::getRazorpaySecret();
        $api = new Api($apikey, $apisecrate);
        $order = $api->order->fetch($id);
        $paymentStatus = $order->status;
        if ($paymentStatus === 'paid') {
            $payment = PackagePurchaseHistroy::where(['tnx_id' => $id, 'user_id' => $user->user_id])->first();
            $payment->payment_status = 2;
            if ($user->planexpired()) {
                $user->plan_id = $payment->plan_id;
                $user->plan_created = $payment->start_date;
                $user->plan_expired = $payment->end_date;
                $user->update();
            }
            $payment->update();
            return redirect()->route('user.plans.list')->with('success', 'Your order placed Successfully');
        } else {
            $payment = PackagePurchaseHistroy::where(['tnx_id' => $id, 'user_id' => $user->user_id])->first();
            $payment->payment_status = 3;
            $payment->update();
            return redirect()->route('user.plans.list')->with('error', 'Some Technical issue with payment');
        }
    }
    public function print($id)
    {
        $user = auth()->user();
        $data = PackagePurchaseHistroy::where(['id' => $id, 'user_id' => $user->user_id,])->first();
        if (!$data) {
            return redirect()->route('user.plans.list')->with('error', __('message.INVALID_REQUEST'));
        }
        return Pdf::loadView('pdf.plan.invoice', compact('data'))
            ->stream('invoice.pdf');
    }
    public function activate($id)
    {
        $user = auth()->user();
        $plan = PackagePurchaseHistroy::where(['id' => $id, 'user_id' => $user->user_id,])->first();
        if (!$plan) {
            return redirect()->route('user.plans.list')->with('error', __('message.INVALID_REQUEST'));
        }
        $user->plan_id = $plan->plan_id;
        $user->plan_created = $plan->start_date;
        $user->plan_expired = $plan->end_date;
        $user->update();
        $plan->status = 1;
        $plan->update();
        return redirect()->route('user.plans.list')->with('success', __('NEW_PLAN_ACTIVATED'));
    }
}
