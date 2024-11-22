<?php

namespace App\Http\Controllers\v1;

use Carbon\Carbon;
use App\Models\Buyers;
use Illuminate\Http\Request;
use App\Models\MilkBuyRecords;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Middleware\SubDairyUserAccess;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class BuyerController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(SubDairyUserAccess::class, only: ['index', 'add', 'edit', 'deleteAll', 'delete']),
        ];
    }
    public function index(Request $request)
    {
        $title = ($request->trash == 1) ?  __('lang.:name Deleted List', ['name' => __('lang.Buyer')])  : __('lang.Buyers List');
        $trash = ($request->trash == 1) ? 1 : 0;
        $user = auth()->user();
        $buyers = Buyers::where(['parent_id' => $user->user_id, 'trash' => $trash])->orderby('id', 'desc')->paginate(env('PER_PAGE_RECORDS'));
        return view('user.costumers.buyers.index', compact('title', 'buyers'));
    }
    public function delete(Request $request)
    {
        $user = auth()->user();
        $buyer = Buyers::where(['parent_id' => $user->user_id, 'buyer_id' => $request->buyer_id])->first();
        if (!$buyer) {
            return response()->json([
                'status' => false,
                'message' => __('message.BUYER_NOT_FOUND')
            ]);
        }
        if ($buyer->trash == 0) {
            $buyer->trash = 1;
            $buyer->save();
            return response()->json([
                'status' => true,
                'message' => __('message.BUYER_DELETED')
            ]);
        } else {
            $buyer->delete();
            return response()->json([
                'status' => true,
                'message' => __('message.BUYER_PERMANENT_DELETE')
            ]);
        }
    }
    public function deleteAll()
    {
        $user = auth()->user();
        Buyers::where('parent_id', $user->user_id)
            ->where('trash', 1)
            ->delete();
        return redirect()->route('user.buyers.list')->with(
            'success',
            __('message.BUYER_PERMANENT_DELETE_ALL')
        );
    }
    public function restore(Request $request)
    {
        $user = auth()->user();
        $buyer = Buyers::where(['parent_id' => $user->user_id, 'buyer_id' => $request->buyer_id, 'trash' => 1])->first();
        if ($buyer) {
            $buyer->trash = 0;
            $buyer->save();
            return response()->json([
                'status' => true,
                'message' => __('message.BUYER_RESTORED')
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => __('message.BUYER_NOT_FOUND')
            ]);
        }
    }
    public function add()
    {
        $title = __('lang.Add :name', ['name' => __('lang.Buyer')]);
        return view('user.costumers.buyers.add', compact('title'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'father_name' => ['required', 'string'],
            'country_code' => ['required'],
            'mobile' => ['required', 'numeric', Rule::when(function () {
                return in_array(request()->input('country_code'), ['91', '+91']);
            }, 'regex:/^[6789]\d{9}$/'), Rule::unique('buyers', 'mobile')],
            'email' => ['nullable', 'email'],
        ]);
        $user = $request->user();
        $buyer = new Buyers();
        $buyer->name = $request->name;
        $buyer->father_name = $request->father_name;
        $buyer->country_code = $request->country_code;
        $buyer->mobile = $request->mobile;
        $buyer->password = bcrypt(123456); // send text message for password

        if (isset($request->email)) {
            $buyer->email = $request->email;
        }
        $buyer->parent_id = $user->user_id;
        $buyer->save();
        return redirect()->route('user.buyers.list')->with('success', __('message.BUYER_ADD'));
    }
    public function edit($buyer_id)
    {
        $user = auth()->user();
        $buyer = Buyers::where(['parent_id' => $user->user_id, 'buyer_id' => $buyer_id, 'trash' => 0])->first();
        if (!$buyer) {
            return redirect()->route('user.buyers.list')->with('error', __('message.BUYER_NOT_FOUND'));
        }
        $title =  __('lang.:name Edit', ['name' => __('lang.Buyer')]);;
        return view('user.costumers.buyers.edit', compact('title', 'buyer'));
    }
    public function update(Request $request, $buyer_id)
    {
        $user = auth()->user();
        $buyer = Buyers::where(['parent_id' => $user->user_id, 'buyer_id' => $buyer_id, 'trash' => 0])->first();
        if (!$buyer) {
            return redirect()->route('user.buyers.list')->with('error', __('message.BUYER_NOT_FOUND'));
        }
        $request->validate([
            'name' => ['required', 'string'],
            'father_name' => ['required', 'string'],
            'country_code' => ['required'],
            'mobile' => ['required', 'numeric', Rule::when(function () {
                return in_array(request()->input('country_code'), ['91', '+91']);
            }, 'regex:/^[6789]\d{9}$/'), Rule::unique('buyers', 'mobile')->ignore($buyer->id)],
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
        $buyer->name = $request->name;
        $buyer->father_name = $request->father_name;
        $buyer->country_code = $request->country_code;
        $buyer->mobile = $request->mobile;
        if (isset($request->email)) {
            $buyer->email = $request->email;
        }
        if (isset($request->is_fixed_rate)) {
            $buyer->is_fixed_rate = 1;
            $buyer->fixed_rate_type = (isset($request->fixed_rate_type)) ? 1 : 0;
            if (isset($request->fixed_rate_type)) {
                $buyer->fat_rate = $request->fat_rate;
            } else {
                $buyer->rate = $request->rate;
            }
        } else {
            $buyer->is_fixed_rate = 0;
        }
        $buyer->update();
        return redirect()->route('user.buyers.list')->with('success', __('message.BUYER_UPDATED'));
    }

    public function view($buyer_id, Request $request)
    {
        $user = auth()->user();
        $buyer = Buyers::where(['parent_id' => $user->user_id, 'buyer_id' => $buyer_id, 'trash' => 0])->first();
        if (!$buyer) {
            return redirect()->route('user.buyers.list')->with('error', __('message.BUYER_NOT_FOUND'));
        }
        $title =  __('lang.:name View', ['name' => __('lang.Buyer')]);
        $records = MilkBuyRecords::query();
        $records = $records->where(['seller_id' => $user->user_id, 'buyer_id' => $buyer_id]);
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
        // return $datas;
        return view('user.costumers.buyers.view', compact('title', 'buyer', 'records', 'datas', 'start_date', 'end_date'));
    }
    public function printRecords($buyer_id, $start_date, $end_date)
    {
        $user = auth()->user();
        $buyer = Buyers::where(['parent_id' => $user->user_id, 'buyer_id' => $buyer_id, 'trash' => 0])->first();
        if (!$buyer) {
            return redirect()->route('user.buyers.list')->with('error', __('message.BUYER_NOT_FOUND'));
        }
        $records = MilkBuyRecords::query();
        $records = $records->where(['seller_id' => $user->user_id, 'buyer_id' => $buyer_id]);
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

        $pdf = Pdf::loadView('pdf.buyers.print',  compact('buyer', 'records', 'datas', 'start_date', 'end_date', 'user'))
            ->setPaper('A4');
        return $pdf->stream('buyer-slip.pdf');
    }
}
