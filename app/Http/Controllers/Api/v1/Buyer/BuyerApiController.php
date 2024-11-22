<?php

namespace App\Http\Controllers\Api\v1\Buyer;

use Carbon\Carbon;
use App\Models\User;
use App\Helper\Helper;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Models\MilkBuyRecords;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BuyerApiController extends Controller
{
    public function info(Request $request)
    {
        $buyer = $this->profile($request->user());
        return Helper::SuccessReturn($buyer, 'BUYER_INFO_FETCHED');
    }
    private function profile($user)
    {
        $dairy_list = UserProfile::where(['user_id' => $user->parent_id])
            ->select('user_id', 'dairy_name')
            // ->pluck('dairy_name','user_id');
            ->first();
        $user->dairy_list = [$dairy_list];
        $user->dairy =  UserProfile::where('user_id', $user->parent_id)->first()->dairy_name;;
        $user->dairy_mob =  User::where('user_id', $user->parent_id)->select('mobile', 'country_code')->first();
        return $user;
    }
    public function profileUpdate(Request $request)
    {
        $buyer = $request->user();
        $rules = [
            'name' => 'required|string',
            'father_name' => 'required|string',
            'country_code' => ['nullable'],
            'mobile' => ['required', 'numeric', Rule::when(function () {
                return in_array(request()->input('country_code'), ['91', '+91']);
            }, 'regex:/^[6789]\d{9}$/'), Rule::unique('buyers', 'mobile')->ignore($buyer->buyer_id, 'buyer_id')],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn([], $validator->errors()->first());
        }
        $buyer->name = $request->input('name');
        $buyer->father_name = $request->input('father_name');
        $buyer->country_code = $request->input('country_code', $buyer->country_code);
        $buyer->mobile = $request->input('mobile');
        $buyer->update();
        $buyer = $this->profile($request->user());
        return Helper::SuccessReturn($buyer, 'PROFILE_UPDATED');
    }
    public function milkRecords(Request $request)
    {
        $buyer = $request->user();
        $rules = [
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn([], $validator->errors()->first());
        }
        $records = MilkBuyRecords::where(['buyer_id' => $buyer->buyer_id, 'seller_id' => $buyer->parent_id])
            ->whereBetween('date', [$request->start_date, $request->end_date])
            ->get();
        return Helper::SuccessReturn($records, 'BUYER_RECORDS_FETCHED');
    }

    public function milkRecordsCountsPerYear(Request $request)
    {
        $buyer = $request->user();
        $rules = [
            'year' => 'required|numeric',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn([], $validator->errors()->first());
        }
        $year = $request->input('year');
        $startDate = $year . '-01-01';
        $endDate = $year . '-12-31';

        $milkBuyRecords = MilkBuyRecords::where([
            'buyer_id' => $buyer->buyer_id,
            'seller_id' => $buyer->parent_id
        ])
            ->whereYear('date', $year)
            ->selectRaw('MONTH(date) as month, COALESCE(SUM(total_price), 0) as total')
            ->groupBy('month');
        $allMonths = range(1, 12);
        $result = collect($allMonths)->map(function ($month) use ($milkBuyRecords) {
            $record = $milkBuyRecords->clone()->having('month', '=', $month)->first();
            $monthName = date('F', strtotime("2024-$month-01"));
            return [
                $monthName => $record ? number_format($record->total, 2) : '0.00'
            ];
        });

        return Helper::SuccessReturn($result, 'BUYER_RECORDS_DATE_RANGE_AMOUNT_SUM');
    }
    public function milkRecordsDetailed(Request $request)
    {
        $buyer = $request->user();
        $rules = [
            'year' => 'required|numeric',
            'month' => 'required|string'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn([], $validator->errors()->first());
        }
        $year = $request->input('year');
        $month = $request->input('month');
        $monthNumber = Carbon::parse("1 $month $year")->month;
        $startDate = Carbon::createFromDate($year, $monthNumber, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $monthNumber, 1)->endOfMonth();
        $result =  MilkBuyRecords::where('buyer_id', $buyer->buyer_id)
            ->where('seller_id', $buyer->parent_id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
        return Helper::SuccessReturn($result, 'BUYER_MILK_DATE_RANGE_RECORDS');
    }
}
