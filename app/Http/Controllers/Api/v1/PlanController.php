<?php

namespace App\Http\Controllers\Api\v1;

use Carbon\Carbon;
use App\Helper\Helper;
use App\Models\Pakeage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\PackagePurchaseHistroy;
use Illuminate\Support\Facades\Validator;
use App\Http\Middleware\IsDairyMiddleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class PlanController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            IsDairyMiddleware::class
        ];
    }

    public function index(Request $request)
    {
        $type = ($request->user()->is_single()) ?  'single' : 'multiple';
        $plans = Pakeage::where(['status' => 'active', 'category' => $type])
            ->whereNot('plan_id', 'Plan_001')->get();
        return Helper::SuccessReturn($plans, 'PLANS_LIST_FOUND');
    }

    public function planPurchase(Request $request)
    {
        $rules = [
            'plan_id' => ['required', Rule::exists('pakeages', 'plan_id')],
        ];
        $user = $request->user();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $plan = Pakeage::where('plan_id', $request->plan_id)->first();
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
        $histroy->plan_id = $request->plan_id;
        $histroy->payment_method = 'Online';
        $histroy->amount = $plan->price;
        $histroy->start_date = $datetime;
        // 'day', 'month', 'year'
        if ($plan->duration_type == 'year') {
            $histroy->end_date = Carbon::parse($datetime)->addYears($plan->duration);
        } else if ($plan->duration_type == 'month') {
            $histroy->end_date =  Carbon::parse($datetime)->addMonths($plan->duration);
        } else {
            $histroy->end_date =   Carbon::parse($datetime)->addDays($plan->duration);
        }
        $histroy->status = ($user->planexpired()) ? 1 : 0;
        $histroy->save();
        if ($user->planexpired()) {
            $user->plan_id = $plan->plan_id;
            $user->plan_id = $plan->plan_id;
            $user->plan_created = $histroy->start_date;
            $user->plan_expired = $histroy->end_date;
            $user->save();
        }
        return Helper::SuccessReturn(null, 'PLAN_PURCHASED_SUCCESSFULLY');
    }
    public function planPurchaseList(Request $request)
    {
        $user = $request->user();
        $query = PackagePurchaseHistroy::where('user_id', $user->user_id);
        if ($request->has('sort_by') && $request->has('sort_order')) {
            $query->orderBy($request->sort_by, $request->sort_order);
        } else {
            $query->orderBy('created_at', 'desc');
        }
        $data = $query->with('plan')->get();
        return Helper::SuccessReturn($data, 'PLAN_PURCHASE_LIST');
        // $perPage = $request->query('per_page', 10);
        // $purchases = $query->paginate($perPage);
        // return Helper::SuccessReturn([
        //     'data' => $purchases->items(),
        //     'pagination' => [
        //         'total' => $purchases->total(),
        //         'current_page' => $purchases->currentPage(),
        //         'per_page' => $perPage,
        //         'last_page' => $purchases->lastPage(),
        //     ],
        // ], 'PLAN_PURCHASE_LIST');
    }
}
