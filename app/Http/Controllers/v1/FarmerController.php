<?php

namespace App\Http\Controllers\v1;

use App\Helper\Helper;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Farmer;
use App\Models\Pakeage;
use Illuminate\Http\Request;
use App\Models\MilkBuyRecords;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Middleware\SubDairyUserAccess;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class FarmerController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(SubDairyUserAccess::class, only: ['index', 'add', 'edit', 'deleteAll', 'delete']),
        ];
    }
    public function index(Request $request)
    {
        $user = auth()->user();
        $title = ($request->trash == 1) ?  __('lang.:name Deleted List', ['name' => __('lang.Farmer')])  : __('lang.Farmers List');
        $trash = ($request->trash == 1) ? 1 : 0;
        $farmers = Farmer::where(['parent_id' => $user->user_id, 'trash' => $trash])->orderby('farmer_id', 'DESC')->paginate(env('PER_PAGE_RECORDS'));
        return view('user.costumers.farmers.index', compact('title', 'farmers'));
    }

    private function farmerLimitCheck()
    {
        $user = auth()->user();
        $pack_id = $user->is_subdairy() ? User::where('user_id', $user->parent_id)->first()->plan_id : $user->plan_id;
        $user_count = Pakeage::where('plan_id', $pack_id)->first()->farmer_count;
        $count = Farmer::where('parent_id', $user->user_id)->count();
        if ($user_count == 0  || $user_count <= $count) {
            return true;
        } else {
            return false;
        }
    }

    public function delete(Request $request)
    {
        $user = auth()->user();
        $farmer = Farmer::where(['parent_id' => $user->user_id, 'farmer_id' => $request->farmer_id])->first();
        if (!$farmer) {
            return response()->json([
                'status' => false,
                'message' => __('message.FARMER_NOT_FOUND')
            ]);
        }
        if ($farmer->trash == 0) {
            $farmer->trash = 1;
            $farmer->save();
            return response()->json([
                'status' => true,
                'message' => __('message.FARMER_DELETED')
            ]);
        } else {
            $farmer->delete();
            return response()->json([
                'status' => true,
                'message' => __('message.FARMER_PERMANENT_DELETE')
            ]);
        }
    }
    public function deleteAll()
    {
        $user = auth()->user();
        Farmer::where('parent_id', $user->user_id)
            ->where('trash', 1)
            ->delete();
        return redirect()->route('user.farmers.list')->with(
            'success',
            __('message.FARMER_PERMANENT_DELETE_ALL')
        );
    }
    public function restore(Request $request)
    {
        $user = auth()->user();
        $farmer = Farmer::where(['parent_id' => $user->user_id, 'farmer_id' => $request->farmer_id, 'trash' => 1])->first();
        if ($farmer) {
            $farmer->trash = 0;
            $farmer->save();
            return Helper::SuccessReturn(null, 'FARMER_RESTORED');
        } else {
            return Helper::FalseReturn(null, 'FARMER_RESTORED');
        }
    }
    public function add()
    {
        if ($this->farmerLimitCheck()) {
            return redirect()->route('user.farmers.list')->with('error', __('message.USER_LIMIT_REACHED'));
        }
        $title = __('lang.Add :name', ['name' => __('lang.Farmer')]);
        return view('user.costumers.farmers.add', compact('title'));
    }
    public function store(Request $request)
    {
        if ($this->farmerLimitCheck()) {
            return redirect()->route('user.dashboard')->with('error', __('message.USER_LIMIT_REACHED'));
        }
        $request->validate([
            'name' => ['required', 'string'],
            'father_name' => ['required', 'string'],
            'country_code' => ['required'],
            'mobile' => ['required', 'numeric', Rule::when(function () {
                return in_array(request()->input('country_code'), ['91', '+91']);
            }, 'regex:/^[6789]\d{9}$/'), Rule::unique('farmers', 'mobile')],
            'email' => ['nullable', 'email'],
        ]);
        $user = $request->user();
        $farmer = new Farmer();
        $farmer->name = $request->name;
        $farmer->father_name = $request->father_name;
        $farmer->country_code = $request->country_code;
        $farmer->mobile = $request->mobile;
        $farmer->password = bcrypt(123456); // send text message for password
        if (isset($request->email)) {
            $farmer->email = $request->email;
        }
        $farmer->parent_id = $user->user_id;
        $farmer->save();
        return redirect()->route('user.farmers.list')->with('success', __('message.FARMER_ADD'));
    }
    public function edit($farmer_id)
    {
        $user = auth()->user();
        $farmer = Farmer::where(['parent_id' => $user->user_id, 'farmer_id' => $farmer_id, 'trash' => 0])->first();
        if (!$farmer) {
            return redirect()->route('user.farmers.list')->with('error', __('message.FARMER_NOT_FOUND'));
        }
        $title =  __('lang.:name Edit', ['name' => __('lang.Farmer')]);;
        return view('user.costumers.farmers.edit', compact('title', 'farmer'));
    }
    public function view($farmer_id, Request $request)
    {
        $user = auth()->user();
        $farmer = Farmer::where(['parent_id' => $user->user_id, 'farmer_id' => $farmer_id, 'trash' => 0])->first();
        if (!$farmer) {
            return redirect()->route('user.farmers.list')->with('error', __('message.FARMER_NOT_FOUND'));
        }
        $title =  __('lang.:name View', ['name' => __('lang.Farmer')]);
        $records = MilkBuyRecords::query();
        $records = $records->where(['buyer_id' => $user->user_id, 'seller_id' => $farmer_id]);
        if ($request->has('start_date')) {
            $end_date = $request->end_date;
            $start_date = $request->start_date;
        } else {
            $end_date = now()->format('Y-m-d');
            $start_date = now()->subDays(9)->format('Y-m-d');
        }
        $records = $records->whereBetween('date', [$start_date, $end_date]);
        $records = $records->orderby('id')->get();


        $datas = [];
        $currentDate = Carbon::parse($start_date);
        while ($currentDate->lte(Carbon::parse($end_date))) {
            $dateString = $currentDate->toDateString();
            $datas[$dateString] = [
                'M' => $records->where('date', $dateString)->where('shift', 'M')->first(),
                'E' => $records->where('date', $dateString)->where('shift', 'E')->first(),
            ];
            $currentDate->addDay();
        }
        return view('user.costumers.farmers.view', compact('title', 'farmer', 'records', 'datas', 'start_date', 'end_date'));
    }
    public function update(Request $request, $farmer_id)
    {
        $user = auth()->user();
        $farmer = Farmer::where(['parent_id' => $user->user_id, 'farmer_id' => $farmer_id, 'trash' => 0])->first();
        if (!$farmer) {
            return redirect()->route('user.farmers.list')->with('error', __('message.FARMER_NOT_FOUND'));
        }
        $request->validate([
            'name' => ['required', 'string'],
            'father_name' => ['required', 'string'],
            'country_code' => ['required'],
            'mobile' => ['required', 'numeric', Rule::when(function () {
                return in_array(request()->input('country_code'), ['91', '+91']);
            }, 'regex:/^[6789]\d{9}$/'), Rule::unique('farmers', 'mobile')->ignore($farmer->id)],
            'email' => ['nullable', 'email'],
            'is_fixed_rate' => ['nullable'],
            'fixed_rate_type' => ['nullable'],
            'rate' => [Rule::requiredIf(function () {
                return request('is_fixed_rate') == "on" && !request()->has('fixed_rate_type');
            })],
            'fat_rate' => [Rule::requiredIf(function () {
                return request('is_fixed_rate') == "on" && request('fixed_rate_type') == "on";
            })],
        ]);

        // is_fixed_rate":"on","fat_rate":"35","rate":"0"
        $farmer->name = $request->name;
        $farmer->father_name = $request->father_name;
        $farmer->country_code = $request->country_code;
        $farmer->mobile = $request->mobile;
        if (isset($request->email)) {
            $farmer->email = $request->email;
        }
        if (isset($request->is_fixed_rate)) {
            $farmer->is_fixed_rate = 1;
            $farmer->fixed_rate_type = (isset($request->fixed_rate_type)) ? 1 : 0;
            if (isset($request->fixed_rate_type)) {
                $farmer->fat_rate = $request->fat_rate;
            } else {
                $farmer->rate = $request->rate;
            }
        } else {
            $farmer->is_fixed_rate = 0;
        }
        $farmer->update();
        return redirect()->route('user.farmers.list')->with('success', __('message.FARMER_UPDATED'));
    }
    public function printRecords($farmer_id, $start_date, $end_date)
    {
        $user = auth()->user();
        $farmer = Farmer::where(['parent_id' => $user->user_id, 'farmer_id' => $farmer_id, 'trash' => 0])->first();
        if (!$farmer) {
            return redirect()->route('user.farmers.list')->with('error', __('message.FARMER_NOT_FOUND'));
        }
        $records = MilkBuyRecords::query();
        $records = $records->where(['buyer_id' => $user->user_id, 'seller_id' => $farmer_id]);
        $records = $records->whereBetween('date', [$start_date, $end_date]);
        $records = $records->orderby('id')->get();
        $datas = [];
        $currentDate = Carbon::parse($start_date);
        while ($currentDate->lte(Carbon::parse($end_date))) {
            $dateString = $currentDate->toDateString();
            $datas[$dateString] = [
                'M' => $records->where('date', $dateString)->where('shift', 'M')->first(),
                'E' => $records->where('date', $dateString)->where('shift', 'E')->first(),
                'D' => $records->where('date', $dateString)->where('shift', 'D')->first(),
            ];
            $currentDate->addDay();
        }

        $pdf = Pdf::loadView('pdf.farmers.print',  compact('farmer', 'records', 'datas', 'start_date', 'end_date', 'user'))
            ->setPaper('A4');
        return $pdf->stream('farmer-slip.pdf');
    }
}
