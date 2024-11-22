<?php

namespace App\Http\Controllers\Api\v1;

use Carbon\Carbon;
use App\Models\User;
use App\Helper\Helper;
use App\Models\Buyers;
use App\Models\Farmer;
use App\Models\UserSettings;
use Illuminate\Http\Request;
use App\Models\MilkRateChart;
use App\Models\MilkBuyRecords;
use App\Models\MilkSaleRecords;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Middleware\IsDairyMiddleware;
use App\Http\Middleware\SubDairyUserAccess;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class MilkController extends Controller implements HasMiddleware

{
    public static function middleware(): array
    {
        return [
            new Middleware(SubDairyUserAccess::class, only: ['buyIndex', 'buyTrash', 'buyTrashEmpty']),
        ];
    }
    public function buyIndex(Request $request)
    {
        $user = $request->user();
        $rules = [
            'shift' => 'required|in:M,E,D',
            'date' => ['required', 'date']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $date = $request->date;
        $data = MilkBuyRecords::where([
            'shift' => $request->shift,
            'buyer_id' => $user->user_id
        ])->whereDate('date', $date)->with('seller')->get();
        return Helper::StatusReturn($data, 'MILK_LIST_FETCHED');
    }

    public function buyAdd(Request $request)
    {
        return $request;
        $user = $request->user();
        $rules = [
            'farmer_id' => ['required', Rule::exists('farmers', 'farmer_id')->where(function ($query) use ($user) {
                return $query->where(['parent_id' => $user->user_id, 'trash' => 0]);
            })],
            'milk_type' => ['required', Rule::in(['Cow', 'Buffalo', 'Mix', 'Others'])],
            'quantity' => 'required|numeric|min:1',
            'shift' => 'required|in:M,E,D',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $farmer = Farmer::where(['parent_id' => $user->user_id, 'farmer_id' => $request->farmer_id, 'trash' => 0])->first();
        if (!$farmer) {
            return Helper::FalseReturn(null, 'FARMER_NOT_FOUND');
        }
        $milkType = ['Cow', 'Buffalo', 'Mix', 'Other'];
        if ($farmer->is_fixed_rate == 1 && $farmer->fixed_rate_type == 0) {
            $record = new MilkBuyRecords();
            $record->seller_id = $request->farmer_id;
            $record->buyer_id = $user->user_id;
            $record->shift = $request->shift;
            $record->milk_type = array_search($request->milk_type, $milkType);
            $record->quantity = $request->quantity;
            $record->bonus = 0; //$request->farmer_id;
            $record->price = $farmer->rate;
            $record->date = Carbon::now();
            $record->total_price =  $request->quantity * $record->price;
            $record->save();
            return Helper::SuccessReturn($record, 'MILK_RECORD_ADD');
        } else if ($farmer->is_fixed_rate == 1 && $farmer->fixed_rate_type == 1) {
            $rules = [
                'fat' => 'required|numeric|min:1',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return Helper::FalseReturn(null, $validator->errors()->first());
            }
            $record = new MilkBuyRecords();
            $record->seller_id = $request->farmer_id;
            $record->buyer_id = $user->user_id;
            $record->shift = $request->shift;
            $record->milk_type = array_search($request->milk_type, $milkType);
            $record->quantity = $request->quantity;
            $record->fat = $request->fat;
            $record->bonus = 0; //$request->farmer_id;
            $record->price = $farmer->fat_rate * $request->fat;
            $record->date = Carbon::now();
            $record->total_price =  $request->quantity * $record->price;
            $record->save();
            return Helper::SuccessReturn($record, 'MILK_RECORD_ADD');
        } else {
            $rules = [
                'fat' => [
                    'required', 'numeric', 'min:1',
                ],
                'snf' => [
                    'required', 'numeric', 'min:1',
                ],
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return Helper::FalseReturn(null, $validator->errors()->first());
            }
            $rate = MilkRateChart::where([
                'user_id' => $user->user_id,
                'chart_type' => 'Purchase',
                'milk_type' => $request->milk_type,
            ])
                ->whereRaw("CAST(fat AS CHAR) = ?", [$request->fat])
                ->whereRaw("CAST(snf AS CHAR) = ?", [$request->snf])
                ->first();
            if (!$rate) {
                return Helper::FalseReturn(null, 'NO_RATE_FOUND');
            }
            $record = new MilkBuyRecords();
            $record->seller_id = $request->farmer_id;
            $record->buyer_id = $user->user_id;
            $record->shift = $request->shift;
            $record->milk_type = array_search($request->milk_type, $milkType);
            $record->quantity = $request->quantity;
            $record->fat = $request->fat;
            $record->snf = $request->snf;
            // $record->clr = $request->clr;
            $record->bonus = 0;
            $record->price = $rate->rate;
            $record->date = Carbon::now();
            $record->total_price =  $request->quantity * $record->price;
            $record->save();
            return Helper::SuccessReturn($record, 'MILK_RECORD_ADD');
        }
    }
    public function buymilkRate(Request $request)
    {
        $user = $request->user();
        $rules = [
            'farmer_id' => ['required', Rule::exists('farmers', 'farmer_id')->where(function ($query) use ($user) {
                return $query->where(['parent_id' => $user->user_id, 'trash' => 0]);
            })],
            'quantity' => ['required', 'numeric']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $farmer = Farmer::where(['parent_id' => $user->user_id, 'farmer_id' => $request->farmer_id])->first();
        if ($farmer->is_fixed_rate == 1) {
            if ($farmer->fixed_rate_type == 1) {
                $rules2 = [
                    'fat' => ['required', 'numeric'],
                ];
                $validator = Validator::make($request->all(), $rules2);
                if ($validator->fails()) {
                    return Helper::FalseReturn(null, $validator->errors()->first());
                }
                $per_unit = $request->fat * $farmer->fat_rate;
                $total = $request->quantity * $per_unit;
                $data = [
                    'per_unit' => number_format($per_unit, 2),
                    'total' => number_format($total, 2)
                ];
                return Helper::SuccessReturn($data, 'RATE_DETAILS');
            } else {
                $per_unit = $farmer->rate;
                $total = $request->quantity * $per_unit;
                $data = [
                    'per_unit' => number_format($per_unit, 2),
                    'total' => number_format($total, 2)
                ];
                return Helper::SuccessReturn($data, 'RATE_DETAILS');
            }
        } else {
            $rules2 = [
                'fat' => ['required', 'numeric'],
                'snf' => ['required', 'numeric'],
                'clr' => ['required', 'numeric']
            ];
            $validator = Validator::make($request->all(), $rules2);
            if ($validator->fails()) {
                return Helper::FalseReturn(null, $validator->errors()->first());
            }
            $rate = MilkRateChart::where([
                'user_id' => $user->user_id,
                'chart_type' => 'Purchase',
                'milk_type' => $request->milk_type,
            ])
                ->whereRaw("CAST(fat AS CHAR) = ?", [$request->fat])
                ->whereRaw("CAST(snf AS CHAR) = ?", [$request->snf])
                ->first();
            if (!$rate) {
                return Helper::FalseReturn(null, 'NO_RATE_FOUND');
            }
            $per_unit = number_format($rate->rate, 2);
            $total = number_format(round($request->quantity * $per_unit, 2), 2);
            $data = [
                'per_unit' => number_format($per_unit, 2),
                'total' => number_format($total, 2)
            ];
            return Helper::SuccessReturn($data, 'RATE_DETAILS');
        }
    }
    public function buyPrint(Request $request)
    {
        $user = $request->user();
        $rules = [
            'record_id' => ['required', Rule::exists('milk_buy_records', 'id')->where(function ($query) use ($user) {
                return $query->where(['buyer_id' => $user->user_id]);
            })],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $record =   MilkBuyRecords::where([
            "id" => $request->record_id,
            'buyer_id' => $user->user_id
        ])->with('seller')->first();
        $size =  UserSettings::getPrintSize($user->user_id);
        $customPaper = array(0, 0, ($size ?? 2) * 72, (5) * 72);
        $pdf = Pdf::loadView('pdf.milk.slip', compact('record', 'user'))
            ->setPaper($customPaper);
            // ->setPaper('A4');
        ;
        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="invoice.pdf"');
    }
    public function buyTrash(Request $request)
    {
        $user = $request->user();
        $rules = [
            'record_id' => ['required', Rule::exists('milk_buy_records', 'id')->where(function ($query) use ($user) {
                return $query->where(['buyer_id' => $user->user_id, "trash" => 0]);
            })],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $record =   MilkBuyRecords::where([
            "id" => $request->record_id,
            'buyer_id' => $user->user_id,
            "trash" => 0,
        ])->first();
        if ($record) {
            $record->trash = 1;
            $record->save();
        }
        return Helper::SuccessReturn(null, 'RECORD_DELETED_SUCCESSFULLY');
    }
    public function buyRestore(Request $request)
    {
        $user = $request->user();
        $rules = [
            'record_id' => ['required', Rule::exists('milk_buy_records', 'id')->where(function ($query) use ($user) {
                return $query->where(['buyer_id' => $user->user_id, "trash" => 1]);
            })],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $record =   MilkBuyRecords::where([
            "id" => $request->record_id,
            'buyer_id' => $user->user_id,
            "trash" => 1,
        ])->first();
        if ($record) {
            $record->trash = 0;
            $record->save();
        }
        return Helper::SuccessReturn(null, 'RECORD_RESTORE_SUCCESSFULLY');
    }
    public function buyTrashList(Request $request)
    {
        $user = $request->user();
        $records = MilkBuyRecords::where([
            'buyer_id' => $user->user_id,
            "trash" => 1,
        ])->with('seller')->get();
        return Helper::SuccessReturn($records, 'RECORDS_FETCHED_SUCCESSFULLY');
    }
    public function buyTrashEmpty(Request $request)
    {
        $user = $request->user();
        MilkBuyRecords::where([
            'buyer_id' => $user->user_id,
            "trash" => 1,
        ])->delete();
        return Helper::SuccessReturn(null, 'TRASH_EMPTIED_SUCCESSFULLY');
    }
    public function sellIndex(Request $request)
    {
        $user = $request->user();
        $rules = [
            'shift' => 'required|in:M,E,D',
            'date' => ['required', 'date']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $date = $request->date;
        $data = MilkSaleRecords::where([
            'shift' => $request->shift,
            'seller_id' => $user->user_id,
            'trash' => 0
        ])->whereDate('date', $date)
            ->withCostumer()
            ->get();
        return Helper::StatusReturn($data, 'MILK_LIST_FETCHED');
    }
    public function sellAdd(Request $request)
    {
        $user = $request->user();
        $rules = [
            'buyer_id' => ['required', Rule::exists('buyers', 'buyer_id')->where(function ($query) use ($user) {
                return $query->where(['parent_id' => $user->user_id, 'trash' => 0]);
            })],
            'milk_type' => ['required', Rule::in(['Cow', 'Buffalo', 'Mix', 'Others'])],
            'quantity' => 'required|numeric|min:1',
            'shift' => 'required|in:M,E,D',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $buyer = Buyers::where(['parent_id' => $user->user_id, 'buyer_id' => $request->buyer_id, 'trash' => 0])->first();
        if (!$buyer) {
            return Helper::FalseReturn(null, 'BUYER_NOT_FOUND');
        }
        $milkType = ['Cow', 'Buffalo', 'Mix', 'Other'];
        if ($buyer->is_fixed_rate == 1 && $buyer->fixed_rate_type == 0) {
            // fixed rate type
            $record = new MilkSaleRecords();
            $record->seller_id = $user->user_id;
            $record->buyer_id = $request->buyer_id;
            $record->shift = $request->shift;
            $record->milk_type = array_search($request->milk_type, $milkType);
            $record->quantity = $request->quantity;
            $record->bonus = 0;
            $record->price = $buyer->rate;
            $record->date = Carbon::now();
            $record->total_price =  $request->quantity * $record->price;
            $record->save();
            return Helper::SuccessReturn($record, 'MILK_RECORD_ADD');
        } else if ($buyer->is_fixed_rate == 1 && $buyer->fixed_rate_type == 1) {
            // fixed fat rate
            $rules = [
                'fat' => 'required|numeric|min:1',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return Helper::FalseReturn(null, $validator->errors()->first());
            }
            $record = new MilkSaleRecords();
            $record->seller_id = $user->user_id;
            $record->buyer_id = $request->buyer_id;
            $record->shift = $request->shift;
            $record->milk_type = array_search($request->milk_type, $milkType);
            $record->quantity = $request->quantity;
            $record->fat = $request->fat;
            $record->bonus = 0;
            $record->price = $buyer->fat_rate * $request->fat;
            $record->date = Carbon::now();
            $record->total_price =  $request->quantity * $record->price;
            $record->save();
            return Helper::SuccessReturn($record, 'MILK_RECORD_ADD');
        } else {
            $rules = [
                'fat' => [
                    'required', 'numeric', 'min:1',
                ],
                'snf' => [
                    'required', 'numeric', 'min:1',
                ],
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return Helper::FalseReturn(null, $validator->errors()->first());
            }
            $rate = MilkRateChart::where([
                'user_id' => $user->user_id,
                'chart_type' => 'Purchase',
                'milk_type' => $request->milk_type,
            ])
                ->whereRaw("CAST(fat AS CHAR) = ?", [$request->fat])
                ->whereRaw("CAST(snf AS CHAR) = ?", [$request->snf])
                ->first();
            if (!$rate) {
                return Helper::FalseReturn(null, 'NO_RATE_FOUND');
            }
            $record = new MilkSaleRecords();
            $record->seller_id = $user->user_id;
            $record->buyer_id = $request->buyer_id;
            $record->shift = $request->shift;
            $record->milk_type = array_search($request->milk_type, $milkType);
            $record->quantity = $request->quantity;
            $record->fat = $request->fat;
            $record->snf = $request->snf;
            $record->bonus = 0;
            $record->price = $rate->rate;
            $record->date = Carbon::now();
            $record->total_price =  $request->quantity * $record->price;
            $record->save();
            return Helper::SuccessReturn($record, 'MILK_RECORD_ADD');
        }
    }
    public function sellmilkRate(Request $request)
    {
        $user = $request->user();
        $rules = [
            'buyer_id' => ['required', Rule::exists('buyers', 'buyer_id')->where(function ($query) use ($user) {
                return $query->where(['parent_id' => $user->user_id, 'trash' => 0]);
            })],
            'quantity' => ['required', 'numeric'],
            'milk_type' => ['required', Rule::in(['Cow', 'Buffalo', 'Mix', 'Others'])],

        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $buyer = Buyers::where(['parent_id' => $user->user_id, 'buyer_id' => $request->buyer_id])->first();
        if ($buyer->is_fixed_rate == 1) {
            if ($buyer->fixed_rate_type == 1) {
                $rules2 = [
                    'fat' => ['required', 'numeric'],
                ];
                $validator = Validator::make($request->all(), $rules2);
                if ($validator->fails()) {
                    return Helper::FalseReturn(null, $validator->errors()->first());
                }
                $per_unit = $request->fat * $buyer->fat_rate;
                $total = $request->quantity * $per_unit;
                $data = [
                    'per_unit' => number_format($per_unit, 2),
                    'total' => number_format($total, 2)
                ];
                return Helper::SuccessReturn($data, 'RATE_DETAILS');
            } else {
                $per_unit = $buyer->rate;
                $total = $request->quantity * $per_unit;
                $data = [
                    'per_unit' => number_format($per_unit, 2),
                    'total' => number_format($total, 2)
                ];
                return Helper::SuccessReturn($data, 'RATE_DETAILS');
            }
        } else {
            $rules2 = [
                'fat' => ['required', 'numeric'],
                'snf' => ['required', 'numeric'],
                'clr' => ['required', 'numeric']
            ];
            $validator = Validator::make($request->all(), $rules2);
            if ($validator->fails()) {
                return Helper::FalseReturn(null, $validator->errors()->first());
            }
            $rate = MilkRateChart::where([
                'user_id' => $user->user_id,
                'chart_type' => 'Sell',
                'milk_type' => $request->milk_type,
            ])
                ->whereRaw("CAST(fat AS CHAR) = ?", [$request->fat])
                ->whereRaw("CAST(snf AS CHAR) = ?", [$request->snf])
                ->first();
            if (!$rate) {
                return Helper::FalseReturn(null, 'NO_RATE_FOUND');
            }
            $per_unit = number_format($rate->rate, 2);
            // $per_unit = number_format(round(((($request->fat / 100) * $request->quantity) * 7) + ((($request->snf / 100) * $request->quantity) * 7), 2), 2); //
            $total = number_format(round($request->quantity * $per_unit, 2), 2);
            $data = [
                'per_unit' => number_format($per_unit, 2),
                'total' => number_format($total, 2)
            ];
            return Helper::SuccessReturn($data, 'RATE_DETAILS');
        }
    }
    public function sellPrint(Request $request)
    {
        $user = $request->user();
        $rules = [
            'record_id' => ['required', Rule::exists('milk_sale_records', 'id')->where(function ($query) use ($user) {
                return $query->where(['seller_id' => $user->user_id]);
            })],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $record =   MilkSaleRecords::where([
            "id" => $request->record_id,
            'seller_id' => $user->user_id
        ])->first();
        $size =  UserSettings::getPrintSize($user->user_id);
        $customPaper = array(0, 0, ($size ?? 2) * 72, (5) * 72);
        $pdf = Pdf::loadView('pdf.milk.slip_buyer', compact('record', 'user'))
            ->setPaper($customPaper);
            // ->setPaper('A4');
        ;
        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="invoice.pdf"');
    }

    public function sellTrash(Request $request)
    {
        $user = $request->user();
        $rules = [
            'record_id' => ['required', Rule::exists('milk_sale_records', 'id')->where(function ($query) use ($user) {
                return $query->where(['seller_id' => $user->user_id, "trash" => 0]);
            })],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $record =   MilkSaleRecords::where([
            "id" => $request->record_id,
            'seller_id' => $user->user_id,
            "trash" => 0,
        ])->first();
        if ($record) {
            $record->trash = 1;
            $record->save();
        }
        return Helper::SuccessReturn(null, 'RECORD_DELETED_SUCCESSFULLY');
    }
    public function sellRestore(Request $request)
    {
        $user = $request->user();
        $rules = [
            'record_id' => ['required', Rule::exists('milk_sale_records', 'id')->where(function ($query) use ($user) {
                return $query->where(['seller_id' => $user->user_id, "trash" => 1]);
            })],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $record =   MilkSaleRecords::where([
            "id" => $request->record_id,
            'seller_id' => $user->user_id,
            "trash" => 1,
        ])->first();
        if ($record) {
            $record->trash = 0;
            $record->save();
        }
        return Helper::SuccessReturn(null, 'RECORD_RESTORE_SUCCESSFULLY');
    }
    public function sellTrashList(Request $request)
    {
        $user = $request->user();
        $records = MilkSaleRecords::where([
            'seller_id' => $user->user_id,
            "trash" => 1,
        ])
            ->withCostumer()
            ->get();
        return Helper::SuccessReturn($records, 'RECORDS_FETCHED_SUCCESSFULLY');
    }
    public function sellTrashEmpty(Request $request)
    {
        $user = $request->user();
        MilkSaleRecords::where([
            'seller_id' => $user->user_id,
            "trash" => 1,
        ])->delete();
        return Helper::SuccessReturn(null, 'TRASH_EMPTIED_SUCCESSFULLY');
    }
}
