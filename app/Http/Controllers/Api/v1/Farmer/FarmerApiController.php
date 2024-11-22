<?php

namespace App\Http\Controllers\Api\v1\Farmer;

use Carbon\Carbon;
use App\Models\User;
use App\Helper\Helper;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Models\MilkBuyRecords;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class FarmerApiController extends Controller
{
    public function info(Request $request)
    {
        $data = $this->profile($request->user());
        return Helper::SuccessReturn($data, 'FARMER_INFO_FETCHED');
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
        $farmer = $request->user();
        $rules = [
            'name' => 'required|string',
            'father_name' => 'required|string',
            'country_code' => ['required'],
            'mobile' => ['required', 'numeric', Rule::when(function () {
                return in_array(request()->input('country_code'), ['91', '+91']);
            }, 'regex:/^[6789]\d{9}$/'), Rule::unique('farmers', 'mobile')->ignore($farmer->farmer_id, 'farmer_id')],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn([], $validator->errors()->first());
        }
        $farmer->name = $request->input('name');
        $farmer->father_name = $request->input('father_name');
        $farmer->country_code = $request->input('country_code', $farmer->country_code);
        $farmer->mobile = $request->input('mobile');
        $farmer->update();
        $data = $this->profile($request->user());
        return Helper::SuccessReturn($data, 'PROFILE_UPDATED');
    }
    public function milkRecords(Request $request)
    {
        $farmer = $request->user();
        $rules = [
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn([], $validator->errors()->first());
        }
        $records = MilkBuyRecords::where(['seller_id' => $farmer->farmer_id, 'buyer_id' => $farmer->parent_id])
            ->whereBetween('date', [$request->start_date, $request->end_date])
            ->get();
        return Helper::SuccessReturn($records, 'FARMER_RECORDS_FETCHED');
    }
    public function milkRecordsCountsPerYear(Request $request)
    {
        $farmer = $request->user();
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
            'seller_id' => $farmer->farmer_id,
            'buyer_id' => $farmer->parent_id
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

        return Helper::SuccessReturn($result, 'FARMER_RECORDS_DATE_RANGE_AMOUNT_SUM');
    }
    public function milkRecordsDetailed(Request $request)
    {
        $farmer = $request->user();
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
        $result =  MilkBuyRecords::where('seller_id', $farmer->farmer_id)
            ->where('buyer_id', $farmer->parent_id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
        return Helper::SuccessReturn($result, 'FARMER_MILK_DATE_RANGE_RECORDS');
    }
    public function logoutAll(Request $request)
    {
        $user =  $request->user();
        $user->tokens()->whereNot('id', $user->token()->id)->delete();
        return Helper::SuccessReturn(null, 'LOGOUT_ALL_SUCCESSFULLY');
    }
}
