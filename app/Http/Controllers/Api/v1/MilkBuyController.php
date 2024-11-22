<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Helper\Helper;
use App\Models\Farmer;
use App\Models\DealerRoles;
use App\Models\UserSettings;
use Illuminate\Http\Request;
use App\Models\MessagesAlert;
use App\Models\MilkRateChart;
use App\Models\MilkBuyRecords;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ChildDairyMilkRecords;
use Illuminate\Support\Facades\Validator;
use App\Http\Middleware\SubDairyUserAccess;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;



class MilkBuyController extends Controller implements HasMiddleware
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
            'shift' => ['required', 'in:M,E,D'],
            'date' => ['required', 'date']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $data = $this->getMilkRecords($request->shift, $user->user_id, $request->date);
        return Helper::StatusReturn($data, 'MILK_LIST_FETCHED');
    }

    private function getMilkRecords($shift, $user_id, $date)
    {
        return  MilkBuyRecords::where([
            // 'shift' => $shift,
            'buyer_id' => $user_id,
            'trash' => false,
        ])->whereDate('date', $date)
            ->WithCostumer()
            ->get();
    }

    private function getUserType()
    {
        $user_types =  [
            [
                'user_type' => 'FAR',
                'name' => 'Farmer'
            ],
        ];
        if (auth()->user()->is_subdairy()) {
            return $user_types;
        }
        $muserData = DealerRoles::all()->map(function ($muser) {
            return [
                'user_type' => $muser->role_id,
                'name' => $muser->short_name,
            ];
        })->toArray();
        return array_merge($user_types, $muserData);
    }
    public function store(Request $request)
    {
        $roles = $this->getUserType();
        $user = $request->user();
        $rules = [
            'supplier_type' => ['required', 'in:' . implode(',', array_column($roles, 'user_type'))],
            'supplier' => [
                'required',
                function ($attribute, $value, $fail) {
                    $supplier_type = request()->input('supplier_type');
                    $user = auth()->user();
                    if ($supplier_type == 'FAR') {
                        $existsRule = Rule::exists('farmers', 'farmer_id')->where('parent_id', $user->user_id)->where('trash', 0);
                    } else {
                        $existsRule = Rule::exists('users', 'user_id')->where('parent_id', $user->user_id)->where('is_blocked', 0)->where('role_id', $supplier_type);
                    }
                    if (!Validator::make([$attribute => $value], [$attribute => $existsRule])->passes()) {
                        $fail("The selected $attribute does not exist.");
                    }
                },
            ],
            'shift' => ['required', 'in:M,E,D'],
            'date' => ['required', 'date'],
            'quantity' => ['required', 'numeric'],
            'milk_type' => ['required', 'in:Cow,Buffalo,Mix,Other'] //0=Cow,1=Buffalo,2=Mix,3=Other
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn([], $validator->errors()->first());
        }
        $mtype = MILK_TYPE[$request->milk_type];;
        if ($request->supplier_type == 'FAR') {
            $farmer = Farmer::where(['parent_id' => $user->user_id, 'farmer_id' => $request->supplier])->first();
            if ($farmer->is_fixed_rate == 1) {
                if ($farmer->fixed_rate_type == 1) {
                    $rules2 = [
                        'fat' => ['required', 'numeric'],
                    ];
                    $validator = Validator::make($request->all(), $rules);
                    if ($validator->fails()) {
                        return Helper::FalseReturn([], $validator->errors()->first());
                    }
                    $per_unit = $request->fat * $farmer->fat_rate;
                    $total = $request->quantity * $per_unit;
                    $entry  = new MilkBuyRecords();
                    $entry->buyer_id = $user->user_id;
                    $entry->seller_id = $farmer->farmer_id;
                    $entry->name = $farmer->name;
                    $entry->country_code = $farmer->country_code;
                    $entry->mobile = $farmer->mobile;
                    $entry->date = $request->date;
                    $entry->shift = $request->shift;
                    $entry->quantity = $request->quantity;
                    $entry->milk_type = $mtype;
                    $entry->fat = $request->fat;
                    $entry->price = $per_unit;
                    $entry->total_price = $total;
                    $entry->save();
                    // $this->makeprint($user, $entry->id);
                } else {
                    $per_unit = $farmer->rate;
                    $total = $request->quantity * $per_unit;
                    $entry  = new MilkBuyRecords();
                    $entry->buyer_id = $user->user_id;
                    $entry->seller_id = $farmer->farmer_id;
                    $entry->name = $farmer->name;
                    $entry->country_code = $farmer->country_code;
                    $entry->mobile = $farmer->mobile;
                    $entry->date = $request->date;
                    $entry->shift = $request->shift;
                    $entry->milk_type = $mtype;
                    $entry->quantity = $request->quantity;
                    $entry->price = $per_unit;
                    $entry->total_price = $total;
                    $entry->save();
                    // $this->makeprint($user, $entry->id);
                }
            } else {
                $rules2 = [
                    'fat' => ['required', 'numeric'],
                    'snf' => ['required', 'numeric'],
                    'clr' => ['required', 'numeric']
                ];
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return Helper::FalseReturn([], $validator->errors()->first());
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
                    return Helper::FalseReturn(null, 'RATE_NOT_FOUND');
                }
                $per_unit = $rate->rate;
                $total = $request->quantity * $per_unit;
                $entry  = new MilkBuyRecords();
                $entry->buyer_id = $user->user_id;
                $entry->seller_id = $farmer->farmer_id;
                $entry->name = $farmer->name;
                $entry->country_code = $farmer->country_code;
                $entry->mobile = $farmer->mobile;
                $entry->date = $request->date;
                $entry->shift = $request->shift;
                $entry->milk_type = $mtype;
                $entry->quantity = $request->quantity;
                $entry->fat = $request->fat;
                $entry->snf = $request->snf;
                $entry->clr = $request->clr;
                $entry->price = $per_unit;
                $entry->total_price = $total;
                $entry->save();
            }
        } else {
            $supplier = User::where(['user_id' => $request->supplier, 'parent_id' => $user->user_id, 'is_blocked' => 0, 'role_id' => $request->supplier_type])->first();
            $rules2 = [
                'fat' => ['required', 'numeric'],
                'snf' => ['required', 'numeric'],
                'clr' => ['required', 'numeric']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return Helper::FalseReturn([], $validator->errors()->first());
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
                return Helper::FalseReturn(null, 'RATE_NOT_FOUND');
            }
            $per_unit = $rate->rate;
            $total = $request->quantity * $per_unit;
            $entry  = new MilkBuyRecords();
            $entry->buyer_id = $user->user_id;
            $entry->seller_id = $supplier->user_id;
            $entry->name = $supplier->name;
            $entry->country_code = $supplier->country_code;
            $entry->mobile = $supplier->mobile;
            $entry->date = $request->date;
            $entry->record_type = 1;
            $entry->shift = $request->shift;
            $entry->milk_type = $mtype;
            $entry->quantity = $request->quantity;
            $entry->price = $per_unit;
            $entry->total_price = $total;
            $entry->save();
            $note = new MessagesAlert();
            $note->user_id = $supplier->user_id;
            $note->message = 'New milk purchase request from ' . $user->name . ' rised.';
            $note->message_type = 2;
            $note->record_id = $entry->id;
            $note->save();
        }
        $data = MilkBuyRecords::where('id', $entry->id)->first();
        return Helper::SuccessReturn($data, 'MILK_RECORD_ADD');
    }
    public function calculateAmount(Request $request)
    {
        $user = auth()->user();
        $roles = $this->getUserType();
        $rules = [
            'supplier_type' => ['required', 'in:' . implode(',', array_column($roles, 'user_type'))],
            'supplier' => [
                'required',
                function ($attribute, $value, $fail) {
                    $supplier_type = request()->input('supplier_type');
                    $user = auth()->user();
                    if ($supplier_type == 'FAR') {
                        $existsRule = Rule::exists('farmers', 'farmer_id')->where('parent_id', $user->user_id)->where('trash', 0);
                    } else {
                        $existsRule = Rule::exists('users', 'user_id')->where('parent_id', $user->user_id)->where('is_blocked', 0)->where('role_id', $supplier_type);
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
        if ($request->supplier_type == 'FAR') {
            $farmer = Farmer::where(['parent_id' => $user->user_id, 'farmer_id' => $request->supplier])->first();
            if ($farmer->is_fixed_rate == 1) {
                if ($farmer->fixed_rate_type == 1) {
                    $rules2 = [
                        'fat' => ['required', 'numeric'],
                    ];
                    $validator = Validator::make($request->all(), $rules2);
                    if ($validator->fails()) {
                        return Helper::FalseReturn([], $validator->errors()->first());
                    }
                    $per_unit = $request->fat * $farmer->fat_rate;
                    $total = $request->quantity * $per_unit;
                    $data = [
                        'per_unit' => number_format($per_unit, 2),
                        'total' => number_format($total, 2),
                    ];
                    return Helper::SuccessReturn($data, 'RATE_DETAILS');
                } else {
                    $per_unit = $farmer->rate;
                    $total = $request->quantity * $per_unit;
                    $data = [
                        'per_unit' => number_format($per_unit, 2),
                        'total' => $total,
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
                    'chart_type' => 'Purchase',
                    'milk_type' => $request->milk_type,
                ])
                    ->whereRaw("CAST(fat AS CHAR) = ?", [$request->fat])
                    ->whereRaw("CAST(snf AS CHAR) = ?", [$request->snf])
                    ->first();
                if (!$rate) {
                    return Helper::FalseReturn([], 'NO_RATE_FOUND');
                }
                $per_unit = $rate->rate; // fate rate formula
                // $per_unit = number_format(round(((($request->fat / 100) * $request->quantity) * 7) + ((($request->snf / 100) * $request->quantity) * 7), 2), 2); //
                $total = number_format(round($request->quantity * $per_unit, 2), 2);
                $data = [
                    'per_unit' => number_format($per_unit, 2),
                    'total' => $total,
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
                'chart_type' => 'Purchase',
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
                'total' => $total,
            ];
            return Helper::SuccessReturn($data, 'RATE_DETAILS');
        }
    }
    public function print(Request $request)
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
        ])
            ->first();
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
    public function  trash(Request $request)
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
    public function  restore(Request $request)
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
    public function  trashList(Request $request)
    {
        $user = $request->user();
        $records = MilkBuyRecords::where([
            'buyer_id' => $user->user_id,
            "trash" => 1,
        ])->with('seller')->get();
        return Helper::SuccessReturn($records, 'RECORDS_FETCHED_SUCCESSFULLY');
    }
    public function  trashEmpty(Request $request)
    {
        $user = $request->user();
        MilkBuyRecords::where([
            'buyer_id' => $user->user_id,
            "trash" => 1,
        ])->delete();
        return Helper::SuccessReturn(null, 'TRASH_EMPTIED_SUCCESSFULLY');
    }
}
