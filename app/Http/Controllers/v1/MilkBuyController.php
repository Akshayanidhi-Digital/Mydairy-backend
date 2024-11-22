<?php

namespace App\Http\Controllers\v1;

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
use Illuminate\Support\Facades\Auth;
use App\Models\ChildDairyMilkRecords;
use Illuminate\Support\Facades\Validator;
use App\Http\Middleware\SubDairyUserAccess;
use App\Models\MilkTransportRecords;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class MilkBuyController extends Controller  implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(SubDairyUserAccess::class, only: ['index', 'destroy', 'delete']),
        ];
    }


    // public function index(){
    //     $title = __('constants.milkbuy');
    //     return view('user.milk_buy.options',compact('title'));
    // }
    public function index(Request $request)
    {
        $title = __('constants.milkbuy');
        $date = (isset($request->date)) ? $request->date : now();
        $shift = (isset($request->shift)) ? $request->shift : 'day';
        $user = auth()->user();
        $mshift = $shift == 'morning' ? 'M' : ($shift == 'evening' ? 'E' : 'D');


        $baseQuery = MilkBuyRecords::query();
        $baseQuery = $baseQuery->where(['trash' => false, 'is_deleted' => false, 'buyer_id' => $user->user_id])->whereDate('date', $date); // 'shift' => $mshift
        $baseQuery = $baseQuery
            ->WithCostumer();
        $milkrecords = (clone $baseQuery)->paginate(env('PER_PAGE_RECORDS'));

        $quantity = (clone $baseQuery)->sum('quantity');
        $fat = (clone $baseQuery)->sum('fat');
        $snf = (clone $baseQuery)->sum('snf');
        $clr = (clone $baseQuery)->sum('clr');
        $total_price = (clone $baseQuery)->sum('total_price');

        $user_types = $this->getUserType();
        return view('user.milk_buy.index', compact('title', 'milkrecords', 'quantity', 'fat', 'snf', 'clr', 'total_price', 'user_types',));
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
    public function supplierList(Request $request)
    {
        $roles = $this->getUserType();
        $user = Auth::user();
        $rules = [
            'supplier_type' => ['required', 'in:' . implode(',', array_column($roles, 'user_type'))],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn([], $validator->errors()->first());
        }
        if ($request->supplier_type != 'FAR') {
            $data = User::where(['parent_id' => $user->user_id, 'is_blocked' => 0, 'role_id' => $request->supplier_type])->select("user_id as farmer_id", "name", "father_name")->get();
        } else {
            $data = Farmer::where(['parent_id' => $user->user_id, 'trash' => 0])->select("farmer_id", "name", "father_name")->get();
        }
        return Helper::SuccessReturn($data, 'DATA_FATCHED');
    }
    public function supplierInfo(Request $request)
    {
        $roles = $this->getUserType();
        $user = Auth::user();
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
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn([], $validator->errors()->first());
        }
        if ($request->supplier_type != 'FAR') {
            $data = User::where(['user_id' => $request->supplier, 'parent_id' => $user->user_id, 'is_blocked' => 0, 'role_id' => $request->supplier_type])->first();
        } else {
            $data = Farmer::where(['farmer_id' => $request->supplier, 'parent_id' => $user->user_id, 'trash' => 0])->first();
        }
        $data2['html'] =  view('user.partials.milk_buy_farmer_details', compact('data'))->render();
        $data2['farmer'] = ($request->supplier_type == 'FAR') ?  $data : null;
        return Helper::SuccessReturn($data2, 'Farmer data found.');
    }
    public function store(Request $request)
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
            'shift' => ['nullable', 'in:morning,evening,day'],
            'date' => ['required', 'date'],
            'quantity' => ['required', 'numeric'],
            'milk_type' => ['required', 'in:Cow,Buffalo,Mix,Other'] //0=Cow,1=Buffalo,2=Mix,3=Other
        ];
        $mtype = MILK_TYPE[$request->milk_type];;
        $request->validate($rules);
        $mshift = $request->shift == 'morning' ? 'M' : ($request->shift == 'evening' ? 'E' : 'D');
        $link = route('user.Milkbuy.index') . '?date=' . $request->date . '&shift=' . $request->shift;
        if ($request->supplier_type == 'FAR') {
            $farmer = Farmer::where(['parent_id' => $user->user_id, 'farmer_id' => $request->supplier])->first();
            if ($farmer->is_fixed_rate == 1) {
                if ($farmer->fixed_rate_type == 1) {
                    $rules2 = [
                        'fat' => ['required', 'numeric'],
                    ];
                    $request->validate($rules2);
                    $per_unit = $request->fat * $farmer->fat_rate;
                    $total = $request->quantity * $per_unit;
                    $new  = new MilkBuyRecords();
                    $new->buyer_id = $user->user_id;
                    $new->seller_id = $farmer->farmer_id;
                    $new->name = $farmer->name;
                    $new->country_code = $farmer->country_code;
                    $new->mobile = $farmer->mobile;
                    $new->date = $request->date;
                    $new->shift = $mshift;
                    $new->quantity = $request->quantity;
                    $new->milk_type = $mtype;
                    $new->fat = $request->fat;
                    $new->price = $per_unit;
                    $new->total_price = $total;
                    $new->save();
                    $this->makeprint($user, $new->id);
                } else {
                    $per_unit = $farmer->rate;
                    $total = $request->quantity * $per_unit;
                    $new  = new MilkBuyRecords();
                    $new->buyer_id = $user->user_id;
                    $new->seller_id = $farmer->farmer_id;
                    $new->name = $farmer->name;
                    $new->country_code = $farmer->country_code;
                    $new->mobile = $farmer->mobile;
                    $new->date = $request->date;
                    $new->shift = $mshift;
                    $new->milk_type = $mtype;
                    $new->quantity = $request->quantity;
                    $new->price = $per_unit;
                    $new->total_price = $total;
                    $new->save();
                    $this->makeprint($user, $new->id);
                }
            } else {
                $rules2 = [
                    'fat' => ['required', 'numeric'],
                    'snf' => ['required', 'numeric'],
                    'clr' => ['required', 'numeric']
                ];
                $request->validate($rules2);
                $rate = MilkRateChart::where([
                    'user_id' => $user->user_id,
                    'chart_type' => 'Purchase',
                    'milk_type' => $request->milk_type,
                ])
                    ->whereRaw("CAST(fat AS CHAR) = ?", [$request->fat])
                    ->whereRaw("CAST(snf AS CHAR) = ?", [$request->snf])
                    ->first();
                if (!$rate) {
                    return redirect()->back()->with('error', __('message.RATE_NOT_FOUND'));
                }
                $per_unit = $rate->rate; // fate rate formula
                $total = $request->quantity * $per_unit;
                $new  = new MilkBuyRecords();
                $new->buyer_id = $user->user_id;
                $new->seller_id = $farmer->farmer_id;
                $new->name = $farmer->name;
                $new->country_code = $farmer->country_code;
                $new->mobile = $farmer->mobile;
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
                $this->makeprint($user, $new->id);
            }
        } else {
            $supplier = User::where(['user_id' => $request->supplier, 'parent_id' => $user->user_id, 'is_blocked' => 0, 'role_id' => $request->supplier_type])->first();
            $rules2 = [
                'fat' => ['required', 'numeric'],
                'snf' => ['required', 'numeric'],
                'clr' => ['required', 'numeric']
            ];
            $request->validate($rules2);
            $rate = MilkRateChart::where([
                'user_id' => $user->user_id,
                'chart_type' => 'Purchase',
                'milk_type' => $request->milk_type,
            ])
                ->whereRaw("CAST(fat AS CHAR) = ?", [$request->fat])
                ->whereRaw("CAST(snf AS CHAR) = ?", [$request->snf])
                ->first();
            if (!$rate) {
                return redirect()->back()->with('error', __('message.RATE_NOT_FOUND'));
            }
            $per_unit = $rate->rate;
            $total = $request->quantity * $per_unit;
            $entry  = new MilkBuyRecords();
            $entry->buyer_id = $user->user_id;
            $entry->seller_id = $supplier->user_id;

            $entry->name = $supplier->name;
            $entry->country_code = $supplier->country_code;
            $entry->mobile = $supplier->mobile;
            $entry->fat = $request->fat;
            $entry->snf = $request->snf;
            $entry->clr = $request->clr;
            $entry->date = $request->date;
            $entry->record_type = 1;
            $entry->shift = $mshift;
            $entry->milk_type = $mtype;
            $entry->quantity = $request->quantity;
            $entry->price = $per_unit;
            $entry->total_price = $total;
            $entry->is_accepted = false;
            $entry->save();
            MilkTransportRecords::create([
                'record_id' => $entry->id,
                'route_id' => User::getRouteID($supplier->user_id)
            ]);
            $note = new MessagesAlert();
            $note->user_id = $supplier->user_id;
            $note->message = 'New milk purchase request from ' . $user->name . ' rised.';
            $note->message_type = 2;
            $note->record_id = $entry->id;
            $note->save();
        }
        $link = route('user.Milkbuy.index') . '?date=' . $request->date . '&shift=' . $request->shift;

        flash()->success(__('message.MILK_RECORD_ADD'));
        return redirect()->to($link)
            ->withInput(['supplier_type' => $request->supplier_type]);
    }
    private function makeprint($user, $id) {}

    public function farmerGetinfo(Request $request)
    {
        $user = Auth::user();
        $rule = [
            'farmer_id' => ['required', Rule::exists('farmers', 'farmer_id')->where(function ($query) use ($user) {
                return $query->where(['parent_id' => $user->user_id, 'trash' => 0]);
            })]
        ];
        $request->validate($rule);
        $farmer = Farmer::where(['parent_id' => $user->user_id, 'farmer_id' => $request->farmer_id, 'trash' => 0])->first();
        if ($farmer) {
            $data['html'] =  view('user.partials.milk_buy_farmer_details', compact('farmer'))->render();
            $data['farmer'] =  $farmer;
            return Helper::SuccessReturn($data, 'Farmer data found.');
        } else {
            return Helper::FalseReturn('', 'Farmer data not found.');
        }
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
    public function print($id)
    {
        $user = auth()->user();
        $record = MilkBuyRecords::find($id);
        if ($record->buyer_id == $user->user_id) {
            $size =  UserSettings::getPrintSize($user->user_id);
            $customPaper = array(0, 0, ($size ?? 2) * 72, (5) * 72);
            $pdf = Pdf::loadView('pdf.milk.slip', compact('record', 'user'))
                ->setPaper($customPaper);
            // ->setPaper('A4');
            return $pdf->stream('slip.pdf');
        } else {
            return redirect()->back()->with('error', __('message.INVALID_REQUEST'));
        }
    }
    public function printAll($date, $shift)
    {
        $user = auth()->user();
        $user = auth()->user();
        // $mshift = $shift == 'morning' ? 'M' : ($shift == 'evening' ? 'E' : 'D');
        $records = MilkBuyRecords::where('buyer_id', $user->user_id)
            ->where('date', $date)
            // ->where('shift', $mshift)
            ->withCostumer()
            ->get();
        $pdf = Pdf::loadView('pdf.milk.AllBuyslip', compact('records', 'shift', 'date', 'user'))
            ->setPaper('A4');
        return $pdf->stream('invoice.pdf');
    }
    public function destroy(Request $request)
    {
        $user = auth()->user();
        $rules = [
            'record_id' => ['required', Rule::exists('milk_buy_records', 'id')->where(function ($query) use ($user) {
                return $query->where(['buyer_id' => $user->user_id, 'trash' => 0]);
            })],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn([], $validator->errors()->first());
        }
        $record = MilkBuyRecords::where(['id' => $request->record_id, 'buyer_id' => $user->user_id, 'trash' => 0])->first();
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
                return $query->where(['buyer_id' => $user->user_id, 'trash' => 1]);
            })],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn([], $validator->errors()->first());
        }
        $record = MilkBuyRecords::where(['id' => $request->record_id, 'buyer_id' => $user->user_id, 'trash' => 1])->first();
        if ($record) {
            $record->delete();
        }
        return Helper::SuccessReturn([], 'MILKBUY_RECORD_PERMENT_DELETED');
    }
    public function restore(Request $request)
    {
        $user = auth()->user();
        $rules = [
            'record_id' => ['required', Rule::exists('milk_buy_records', 'id')->where(function ($query) use ($user) {
                return $query->where(['buyer_id' => $user->user_id, 'trash' => 1]);
            })],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn([], $validator->errors()->first());
        }
        $record = MilkBuyRecords::where(['id' => $request->record_id, 'buyer_id' => $user->user_id, 'trash' => 1])->first();
        if ($record) {
            $record->trash = 0;
            $record->update();
        }
        return Helper::SuccessReturn([], 'MILKBUY_RECORD_RESTORED');
    }
}
