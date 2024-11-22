<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Helper\Helper;
use App\Models\Buyers;
use App\Models\DealerRoles;
use App\Models\UserSettings;
use Illuminate\Http\Request;
use App\Models\MilkRateChart;
use App\Models\MilkSaleRecords;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Middleware\SubDairyUserAccess;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;


class MilkSaleController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware(SubDairyUserAccess::class, only: ['index', 'trash', 'trashEmpty']),
        ];
    }
    public function index(Request $request)
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
        $data = $this->getMilkRecords($request->shift, $user->user_id, $date);
        return Helper::StatusReturn($data, 'MILK_LIST_FETCHED');
    }
    private function getMilkRecords($shift, $user_id, $date)
    {
        return  MilkSaleRecords::where([
            'shift' => $shift,
            'seller_id' => $user_id,
            'trash' => 0
        ])->whereDate('date', $date)
            ->withCostumer()
            ->get();
    }
    private function getUserType()
    {
        $user_types =  [
            [
                'user_type' => 'BYR',
                'name' => 'Buyer'
            ],

        ];
        if (!auth()->user()->is_subdairy()) {
            $muserData = DealerRoles::all()->map(function ($muser) {
                return [
                    'user_type' => $muser->role_id,
                    'name' => $muser->short_name,
                ];
            })->toArray();
            $user_types = array_merge($user_types, $muserData);
        }
        return array_merge($user_types, [
            [
                'user_type' => 'EXT',
                'name' => 'Other'
            ],
        ]);
    }
    public function store(Request $request)
    {
        $roles = $this->getUserType();
        $user = auth()->user();
        $rules = [
            'buyer_type' => ['required', 'in:' . implode(',', array_column($roles, 'user_type'))],
            'buyer' => [
                'required',
                function ($attribute, $value, $fail) {
                    $buyer_type = request()->input('buyer_type');
                    $user = auth()->user();
                    if ($buyer_type == 'BYR') {
                        $existsRule = Rule::exists('buyers', 'buyer_id')->where('parent_id', $user->user_id)->where('trash', 0);
                    } else if ($buyer_type == 'EXT') {
                        return;
                    } else {
                        $existsRule = Rule::exists('users', 'user_id')->where('parent_id', $user->user_id)->where('is_blocked', 0)->where('role_id', $buyer_type);
                    }
                    if (!Validator::make([$attribute => $value], [$attribute => $existsRule])->passes()) {
                        $fail("The selected $attribute does not exist.");
                    }
                },
            ],
            'name' => ['nullable', Rule::requiredIf(function () use ($request) {
                return $request->buyer_type == 'EXT';
            }), 'string'],
            'country_code' => ['nullable', Rule::requiredIf(function () use ($request) {
                return $request->has('mobile');
            }), 'string'],
            'mobile' => ['nullable', 'numeric', Rule::when(function () {
                return in_array(request()->input('country_code'), ['91', '+91']);
            }, 'regex:/^[6789]\d{9}$/'),],
            'shift' => 'required|in:M,E,D',
            'date' => ['required', 'date'],
            'quantity' => ['required', 'numeric'],
            'milk_type' => ['required', 'in:Cow,Buffalo,Mix'] //0=Cow,1=Buffalo,2=Mix,3=Other
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $mtype = MILK_TYPE[$request->milk_type];

        if ($request->buyer_type == 'EXT') {
            $rules2 = [
                'fat' => ['required', 'numeric'],
                'snf' => ['required', 'numeric'],
                'clr' => ['required', 'numeric']
            ];
            $rate = MilkRateChart::where([
                'user_id' => $user->user_id,
                'chart_type' => 'Sell',
                'milk_type' => $request->milk_type,
            ])
                ->whereRaw("CAST(fat AS CHAR) = ?", [$request->fat])
                ->whereRaw("CAST(snf AS CHAR) = ?", [$request->snf])
                ->first();
            if (!$rate) {
                return redirect()->back()->with('error', __('message.NO_RATE_FOUND'));
                // return Helper::FalseReturn([], 'NO_RATE_FOUND');
            }
            $per_unit = $rate->rate;
            $total = $request->quantity * $per_unit;
            $new = new MilkSaleRecords();
            $new->seller_id = $user->user_id;
            $new->record_type = 2;
            $new->date = $request->date;
            $new->shift = $request->shift;
            $new->quantity = $request->quantity;
            $new->milk_type = $mtype;
            $new->fat = $request->fat;
            $new->price = $per_unit;
            $new->total_price = $total;
            $new->name = $request->input('name');
            $new->country_code = $request->input('country_code', '+91');
            $new->mobile = $request->input('mobile', null);
            $new->save();
        } else if ($request->buyer_type != 'BYR') {
            $rules2 = [
                'fat' => ['required', 'numeric'],
                'snf' => ['required', 'numeric'],
                'clr' => ['required', 'numeric']
            ];
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
            $per_unit = $rate->rate;
            $total = $request->quantity * $per_unit;
            $buyer = User::where(['user_id' => $request->buyer, 'parent_id' => $user->user_id, 'is_blocked' => 0,])->first();
            if (!$buyer) {
                return Helper::FalseReturn(null, 'NO_USER_FOUND');
            }
            $new = new MilkSaleRecords();
            $new->buyer_id = $buyer->user_id;
            $new->seller_id = $user->user_id;

            $new->name = $buyer->name;
            $new->country_code = $buyer->country_code;
            $new->mobile = $buyer->mobile;

            $new->record_type = 1;
            $new->date = $request->date;
            $new->shift = $request->shift;
            $new->quantity = $request->quantity;
            $new->milk_type = $mtype;
            $new->fat = $request->fat;
            $new->price = $per_unit;
            $new->total_price = $total;
            $new->save();
        } else {
            $buyer = Buyers::where(['parent_id' => $user->user_id, 'buyer_id' => $request->buyer])->first();
            if ($buyer->is_fixed_rate == 1) {
                if ($buyer->fixed_rate_type == 1) {
                    $rules2 = [
                        'fat' => ['required', 'numeric'],
                    ];
                    $request->validate($rules2);
                    $per_unit = $request->fat * $buyer->fat_rate;
                    $total = $request->quantity * $per_unit;
                    $new  = new MilkSaleRecords();
                    $new->buyer_id = $buyer->buyer_id;
                    $new->seller_id = $user->user_id;

                    $new->name = $buyer->name;
                    $new->country_code = $buyer->country_code;
                    $new->mobile = $buyer->mobile;

                    $new->date = $request->date;
                    $new->shift = $request->shift;
                    $new->quantity = $request->quantity;
                    $new->milk_type = $mtype;
                    $new->fat = $request->fat;
                    $new->price = $per_unit;
                    $new->total_price = $total;
                    $new->save();
                } else {
                    $per_unit = $buyer->rate;
                    $total = $request->quantity * $per_unit;
                    $new  = new MilkSaleRecords();
                    $new->buyer_id = $buyer->buyer_id;
                    $new->seller_id = $user->user_id;
                    $new->name = $buyer->name;
                    $new->country_code = $buyer->country_code;
                    $new->mobile = $buyer->mobile;
                    $new->date = $request->date;
                    $new->shift = $request->shift;
                    $new->milk_type = $mtype;
                    $new->quantity = $request->quantity;
                    $new->price = $per_unit;
                    $new->total_price = $total;
                    $new->save();
                }
            } else {
                $rules2 = [
                    'fat' => ['required', 'numeric'],
                    'snf' => ['required', 'numeric'],
                    'clr' => ['required', 'numeric']
                ];
                $rate = MilkRateChart::where([
                    'user_id' => $user->user_id,
                    'chart_type' => 'Sell',
                    'milk_type' => $request->milk_type,
                ])
                    ->whereRaw("CAST(fat AS CHAR) = ?", [$request->fat])
                    ->whereRaw("CAST(snf AS CHAR) = ?", [$request->snf])
                    ->first();
                if (!$rate) {
                    return Helper::FalseReturn([], 'NO_RATE_FOUND');
                }
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return Helper::FalseReturn(null, $validator->errors()->first());
                }
                $per_unit = $rate->rate;
                $total = $request->quantity * $per_unit;
                $new  = new MilkSaleRecords();
                $new->buyer_id = $buyer->buyer_id;
                $new->seller_id = $user->user_id;
                $new->name = $buyer->name;
                $new->country_code = $buyer->country_code;
                $new->mobile = $buyer->mobile;
                $new->date = $request->date;
                $new->shift = $request->shift;
                $new->milk_type = $mtype;
                $new->quantity = $request->quantity;
                $new->fat = $request->fat;
                $new->snf = $request->snf;
                $new->clr = $request->clr;
                $new->price = $per_unit;
                $new->total_price = $total;
                $new->save();
            }
        }
        $data = MilkSaleRecords::where('id', $new->id)->first();
        return Helper::SuccessReturn($data, 'MILK_RECORD_ADD');
    }
    public function calculateAmount(Request $request)
    {
        $roles = $this->getUserType();
        $user = auth()->user();
        $rules = [
            'buyer_type' => ['required', 'in:' . implode(',', array_column($roles, 'user_type'))],
            'buyer' => [
                'required',
                function ($attribute, $value, $fail) {
                    $buyer_type = request()->input('buyer_type');
                    $user = auth()->user();
                    if ($buyer_type == 'BYR') {
                        $existsRule = Rule::exists('buyers', 'buyer_id')->where('parent_id', $user->user_id)->where('trash', 0);
                    } else if ($buyer_type == 'EXT') {
                        return;
                    } else {
                        $existsRule = Rule::exists('users', 'user_id')->where('parent_id', $user->user_id)->where('is_blocked', 0)->where('role_id', $buyer_type);
                    }
                    if (!Validator::make([$attribute => $value], [$attribute => $existsRule])->passes()) {
                        $fail("The selected $attribute does not exist.");
                    }
                },
            ],
            'quantity' => ['required', 'numeric'],
            'milk_type' => ['required', 'in:Cow,Buffalo,Mix,Other'] //0=Cow,1=Buffalo,2=Mix,3=Other
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn([], $validator->errors()->first());
        }
        if ($request->buyer_type == 'BYR') {
            $buyer = Buyers::where(['parent_id' => $user->user_id, 'buyer_id' => $request->buyer])->first();
            if ($buyer->is_fixed_rate == 1) {
                if ($buyer->fixed_rate_type == 1) {
                    $rules2 = [
                        'fat' => ['required', 'numeric'],
                    ];
                    $validator = Validator::make($request->all(), $rules2);
                    if ($validator->fails()) {
                        return Helper::FalseReturn([], $validator->errors()->first());
                    }
                    $per_unit = $request->fat * $buyer->fat_rate;
                    $total = $request->quantity * $per_unit;
                    $data = [
                        'per_unit' => number_format($per_unit, 2),
                        'total' => number_format($total, 2),
                    ];
                    return Helper::SuccessReturn($data, 'RATE_DETAILS');
                } else {
                    $per_unit = $buyer->rate;
                    $total = $request->quantity * $per_unit;
                    $data = [
                        'per_unit' => number_format($per_unit, 2),
                        'total' => number_format($total, 2),
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
                    return Helper::FalseReturn([], $validator->errors()->first());
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
                    return Helper::FalseReturn([], 'NO_RATE_FOUND');
                }
                $per_unit = $rate->rate;
                $total = number_format(round($request->quantity * $per_unit, 2), 2);
                $data = [
                    'per_unit' => number_format($per_unit, 2),
                    'total' => number_format($total, 2),
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
                return Helper::FalseReturn([], $validator->errors()->first());
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
                return Helper::FalseReturn([], 'NO_RATE_FOUND');
            }
            $per_unit = $rate->rate;
            $total = number_format(round($request->quantity * $per_unit, 2), 2);
            $data = [
                'per_unit' => number_format($per_unit, 2),
                'total' => number_format($total, 2),
            ];
            return Helper::SuccessReturn($data, 'RATE_DETAILS');
        }
    }
    public function print(Request $request)
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
    public function  trash(Request $request)
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
    public function  restore(Request $request)
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
    public function  trashList(Request $request)
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
    public function  trashEmpty(Request $request)
    {
        $user = $request->user();
        MilkSaleRecords::where([
            'seller_id' => $user->user_id,
            "trash" => 1,
        ])->delete();
        return Helper::SuccessReturn(null, 'TRASH_EMPTIED_SUCCESSFULLY');
    }
}
