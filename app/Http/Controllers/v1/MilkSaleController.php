<?php

namespace App\Http\Controllers\v1;

use App\Models\User;
use App\Models\buyer;
use App\Helper\Helper;
use App\Models\Buyers;
use Mike42\Escpos\Printer;
use App\Models\DealerRoles;
use App\Models\UserSettings;
use Illuminate\Http\Request;
use App\Models\MilkRateChart;
use App\Models\MilkSaleRecords;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Middleware\SubDairyUserAccess;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

class MilkSaleController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware(SubDairyUserAccess::class, only: ['index', 'destroy', 'delete']),
        ];
    }

    // public function index(){
    //     $title = __('constants.milksell_p');
    //     return view('user.milk_sell.options',compact('title'));
    // }
    public function index(Request $request)
    {
        // return redirect()->route('user.dashboard')->with('error','Milk Sell module access temporarily blocked');
        $title = __('constants.milksell');
        $date = (isset($request->date)) ? $request->date : now();
        $shift = (isset($request->shift)) ? $request->shift : 'day';
        $user = auth()->user();
        $mshift = $shift == 'morning' ? 'M' : ($shift == 'evening' ? 'E' : 'D');
        $baseQuery = MilkSaleRecords::query();
        $baseQuery = $baseQuery->where(['trash' => false, 'is_deleted' => false, 'seller_id' => $user->user_id,])->whereDate('date', $date); //'shift' => $mshift
        $baseQuery = $baseQuery
            ->WithCostumer();
        $milkrecords = (clone $baseQuery)->paginate(env('PER_PAGE_RECORDS'));

        $quantity = (clone $baseQuery)->sum('quantity');
        $fat = (clone $baseQuery)->sum('fat');
        $snf = (clone $baseQuery)->sum('snf');
        $clr = (clone $baseQuery)->sum('clr');
        $total_price = (clone $baseQuery)->sum('total_price');
        $buyers = Buyers::where(['parent_id' => $user->user_id, 'trash' => false])
            ->select('buyer_id as user_id', 'name')->get();
        $user_types = $this->getUserType();
        return view('user.milk_sell.index', compact('title', 'user_types', 'milkrecords', 'quantity', 'fat', 'snf', 'clr', 'total_price', 'buyers'));
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
    public function buyerList(Request $request)
    {
        $roles = $this->getUserType();
        $user = Auth::user();
        $rules = [
            'buyer_type' => ['required', 'in:' . implode(',', array_column($roles, 'user_type'))],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn([], $validator->errors()->first());
        }
        if ($request->buyer_type == 'EXT') {
            $data['form'] = view('user.milk_sell.form', ['type' => 'another'])->render();
            return Helper::SuccessReturn($data, 'DATA_FATCHED');
        } else {
            $data['form'] = view('user.milk_sell.form', ['type' => 'default'])->render();
        }
        if ($request->buyer_type != 'BYR') {
            $data['buyer'] = User::where(['parent_id' => $user->user_id, 'is_blocked' => 0, 'role_id' => $request->buyer_type])->select("user_id as buyer_id", "name", "father_name")->get();
        } else {
            $data['buyer'] = Buyers::where(['parent_id' => $user->user_id, 'trash' => 0])->select("buyer_id", "name", "father_name")->get();
        }
        return Helper::SuccessReturn($data, 'DATA_FATCHED');
    }




    public function store(Request $request)
    {
        // $connector = new FilePrintConnector("php://stdout");
        //                 $printer = new Printer($connector);
        //                 // Print receipt content
        //                 $printer->text("Hello, World!\n");
        //                 $printer->text("This is a test receipt.\n");
        //                 $printer->cut();

        //                 // Close the printer
        //                 $printer->close();
        //                 return "hello";
        //                 die('i am here');
        $roles = $this->getUserType();
        $user = auth()->user();
        $rules = [
            'buyer_type' => ['required', 'in:' . implode(',', array_column($roles, 'user_type'))],
            'buyer' => [
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
            'shift' => ['nullable', 'in:morning,evening,day'],
            'date' => ['required', 'date'],
            'quantity' => ['required', 'numeric'],
            'milk_type' => ['required', 'in:Cow,Buffalo,Mix,Other'] //0=Cow,1=Buffalo,2=Mix,3=Other
        ];
        $request->validate($rules);
        $mtype = MILK_TYPE[$request->milk_type];
        $mshift = $request->shift == 'morning' ? 'M' : ($request->shift == 'evening' ? 'E' : 'D');

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
            $saleRecord = new MilkSaleRecords();
            $saleRecord->seller_id = $user->user_id;
            $saleRecord->record_type = 2;
            $saleRecord->date = $request->date;
            $saleRecord->shift = $mshift;
            $saleRecord->quantity = $request->quantity;
            $saleRecord->milk_type = $mtype;
            $saleRecord->fat = $request->fat;
            $saleRecord->price = $per_unit;
            $saleRecord->total_price = $total;
            $saleRecord->name = $request->input('name');
            $saleRecord->country_code = $request->input('country_code', '+91');
            $saleRecord->mobile = $request->input('mobile', null);
            $saleRecord->save();
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
                return redirect()->back()->with('error', __('message.NO_RATE_FOUND'));
                // return Helper::FalseReturn([], 'NO_RATE_FOUND');
            }
            $per_unit = $rate->rate;
            $total = $request->quantity * $per_unit;
            $buyer = User::where(['user_id' => $request->buyer, 'parent_id' => $user->user_id, 'is_blocked' => 0,])->first();
            if (!$buyer) {
                return redirect()->back()->with('error', __('message.NO_USER_FOUND'));
            }
            $saleRecord = new MilkSaleRecords();
            $saleRecord->buyer_id = $buyer->user_id;
            $saleRecord->seller_id = $user->user_id;

            $saleRecord->name = $buyer->name;
            $saleRecord->country_code = $buyer->country_code;
            $saleRecord->mobile = $buyer->mobile;

            $saleRecord->record_type = 1;
            $saleRecord->date = $request->date;
            $saleRecord->shift = $mshift;
            $saleRecord->quantity = $request->quantity;
            $saleRecord->milk_type = $mtype;
            $saleRecord->fat = $request->fat;
            $saleRecord->price = $per_unit;
            $saleRecord->total_price = $total;
            $saleRecord->save();
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
                    $new->shift = $mshift;
                    $new->quantity = $request->quantity;
                    $new->milk_type = $mtype;
                    $new->fat = $request->fat;
                    $new->price = $per_unit;
                    $new->total_price = $total;
                    $new->save();
                    $link = route('user.MilkSell.index') . '?date=' . $request->date . '&shift=' . $request->shift;
                    return redirect()->to($link)
                        ->with('success', __('message.MILK_RECORD_ADD'));
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
                    $new->shift = $mshift;
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
                $request->validate($rules2);
                $per_unit = $rate->rate;
                $total = $request->quantity * $per_unit;
                $new  = new MilkSaleRecords();
                $new->buyer_id = $buyer->buyer_id;
                $new->seller_id = $user->user_id;
                $new->name = $buyer->name;
                $new->country_code = $buyer->country_code;
                $new->mobile = $buyer->mobile;
                $new->date = $request->date;
                $new->shift = $mshift;
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

        $link = route('user.MilkSell.index') . '?date=' . $request->date . '&shift=' . $request->shift;
        return redirect()->to($link)
            ->with('success', __('message.MILK_RECORD_ADD'))->withInput(['buyer_type' => $request->buyer_type]);
    }

    public function buyerGetinfo(Request $request)
    {
        $roles = $this->getUserType();
        $user = Auth::user();
        $rules = [
            'buyer_type' => ['required', 'in:' . implode(',', array_column($roles, 'user_type'))],
            'buyer' => [
                'required',
                function ($attribute, $value, $fail) {
                    $buyer_type = request()->input('buyer_type');
                    $user = auth()->user();
                    if ($buyer_type == 'BYR') {
                        $existsRule = Rule::exists('buyers', 'buyer_id')->where('parent_id', $user->user_id)->where('trash', 0);
                    } else {
                        $existsRule = Rule::exists('users', 'user_id')->where('parent_id', $user->user_id)->where('is_blocked', 0)->where('role_id', $buyer_type);
                    }
                    if (!Validator::make([$attribute => $value], [$attribute => $existsRule])->passes()) {
                        $fail("The selected $attribute does not exist.");
                    }
                },
            ],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn([], $validator->errors()->first());
        }
        if ($request->buyer_type != 'BYR') {
            $data = User::where(['user_id' => $request->buyer, 'parent_id' => $user->user_id, 'is_blocked' => 0, 'role_id' => $request->buyer_type])->first();
        } else {
            $data = Buyers::where(['buyer_id' => $request->buyer, 'parent_id' => $user->user_id, 'trash' => 0])->first();
        }
        $data2['html'] =  view('user.partials.milk_buy_buyer_details', compact('data'))->render();
        $data2['buyer'] = ($request->buyer_type == 'BYR') ?  $data : null;
        return Helper::SuccessReturn($data2, 'Buyer data found.');
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
    public function print($id)
    {
        $user = auth()->user();
        $record = MilkSaleRecords::find($id);
        if ($record->seller_id == $user->user_id) {
            $size =  UserSettings::getPrintSize($user->user_id);
            $customPaper = array(0, 0, ($size ?? 2) * 72, (5) * 72);
            $pdf = Pdf::loadView('pdf.milk.slip_buyer', compact('record', 'user'))
                ->setPaper($customPaper);
            // ->setPaper('A4');
            return $pdf->stream('invoice.pdf');
        } else {
            return redirect()->back()->with('error', __('message.INVALID_REQUEST'));
        }
    }
    // public function printAll($date, $shift)
    public function printAll($date)
    {
        $user = auth()->user();
        $user = auth()->user();
        // $mshift = $shift == 'morning' ? 'M' : ($shift == 'evening' ? 'E' : 'D');
        $records = MilkSaleRecords::where('seller_id', $user->user_id)
            ->where('date', $date)
            // ->where('shift', $mshift)
            ->withCostumer()
            ->get();
        // $pdf = Pdf::loadView('pdf.milk.AllSellslip', compact('records', 'shift', 'date', 'user'))
        $pdf = Pdf::loadView('pdf.milk.AllSellslip', compact('records', 'date', 'user'))
            ->setPaper('A4');
        return $pdf->stream('invoice.pdf');
    }
    public function destroy(Request $request)
    {
        $user = auth()->user();
        $rules = [
            'record_id' => ['required', Rule::exists('milk_buy_records', 'id')->where(function ($query) use ($user) {
                return $query->where(['seller_id' => $user->user_id, 'trash' => 0]);
            })],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn([], $validator->errors()->first());
        }
        $record = MilkSaleRecords::where(['id' => $request->record_id, 'seller_id' => $user->user_id, 'trash' => 0])->first();
        if ($record) {
            $record->trash = 1;
            $record->update();
        }
        return Helper::SuccessReturn([], 'MILKBUY_RECORD_DELETED');
    }
    public function delete(Request $request)
    {
        $user = auth()->user();
        $rules = [
            'record_id' => ['required', Rule::exists('milk_buy_records', 'id')->where(function ($query) use ($user) {
                return $query->where(['seller_id' => $user->user_id, 'trash' => 1]);
            })],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn([], $validator->errors()->first());
        }
        $record = MilkSaleRecords::where(['id' => $request->record_id, 'seller_id' => $user->user_id, 'trash' => 1])->first();
        if ($record) {
            $record->delete();
        }
        return Helper::SuccessReturn([], 'MILKSELL_RECORD_PERMENT_DELETED');
    }
    public function restore(Request $request)
    {
        $user = auth()->user();
        $rules = [
            'record_id' => ['required', Rule::exists('milk_buy_records', 'id')->where(function ($query) use ($user) {
                return $query->where(['seller_id' => $user->user_id, 'trash' => 1]);
            })],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn([], $validator->errors()->first());
        }
        $record = MilkSaleRecords::where(['id' => $request->record_id, 'seller_id' => $user->user_id, 'trash' => 1])->first();
        if ($record) {
            $record->trash = 0;
            $record->update();
        }
        return Helper::SuccessReturn([], 'MILKSELL_RECORD_RESTORED');
    }
}
