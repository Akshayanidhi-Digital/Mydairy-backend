<?php

namespace App\Http\Controllers\v1;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Middleware\SubDairyUserAccess;
use App\Models\MilkBuyRecords;
use App\Models\MilkSaleRecords;
use App\Models\UserSettings;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;

class RecordsController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware(SubDairyUserAccess::class, except: ['milkRequests', 'milkRequestsView', 'milkBuyPrint', 'milkSellPrint']),
        ];
    }
    public function milkRequests(Request $request)
    {
        if (auth()->user()->is_subdairy()) {
            $title = __('lang.Milk Request');
            if ($request->date) {
                $date = $request->input('date');
            } else {
                $date = Carbon::now()->format('Y-m-d');
            }
            if ($request->shift) {
                $mshift = $request->shift == 'morning' ? 'M' : ($request->shift == 'evening' ? 'E' : 'D');
            }
            $user = auth()->user();
            $baseQuery =  MilkBuyRecords::query();
            $baseQuery = $baseQuery->where(['seller_id' => $user->user_id, 'trash' => 0]);
            $baseQuery = $baseQuery->WithTransportDetails();
            $baseQuery = $baseQuery->with('buyer', 'transportdetails');
            $baseQuery =  $baseQuery->whereDate('date', $date);
            if ($request->shift &&  ($request->shift == 'morning' || $request->shift == 'evening')) {
                $baseQuery = $baseQuery->where('shift', $mshift);
            }
            if ($request->milk_type && array_key_exists($request->milk_type, MILK_TYPE)) {
                $baseQuery = $baseQuery->where('milk_type', MILK_TYPE[$request->milk_type]);
            }
            $datas = (clone $baseQuery)->orderby('created_at', 'desc')->paginate(env('PER_PAGE_RECORDS'));
            $quantity = (clone $baseQuery)->sum('quantity');
            $fat = (clone $baseQuery)->sum('fat');
            $snf = (clone $baseQuery)->sum('snf');
            $clr = (clone $baseQuery)->sum('clr');
            $total_price = (clone $baseQuery)->sum('total_price');
            return view('user.records.milk_buy.request', compact('title', 'datas', 'quantity', 'fat', 'snf', 'clr', 'total_price'));
        } else {
            flash()->error(__('message.INVALID_REQUEST'));
            return redirect()->route('user.dashboard');
        }
    }
    public function milkRequestsView($record_id, Request $request)
    {

        if (auth()->user()->is_subdairy()) {
            if ($request->date) {
                $date = $request->input('date');
            } else {
                $date = Carbon::now()->format('Y-m-d');
            }
            if ($request->shift) {
                $mshift = $request->shift == 'morning' ? 'M' : ($request->shift == 'evening' ? 'E' : 'D');
            }
            $user = auth()->user();
            $baseQuery =  MilkBuyRecords::query();
            $baseQuery = $baseQuery->where(['id' => $record_id, 'seller_id' => $user->user_id, 'trash' => 0]);
            $baseQuery = $baseQuery->WithTransportDetails();
            $baseQuery = $baseQuery->with('buyer', 'transportdetails');
            $baseQuery =  $baseQuery->whereDate('date', $date);
            if ($request->shift &&  ($request->shift == 'morning' || $request->shift == 'evening')) {
                $baseQuery = $baseQuery->where('shift', $mshift);
            }
            if ($request->milk_type && array_key_exists($request->milk_type, MILK_TYPE)) {
                $baseQuery = $baseQuery->where('milk_type', MILK_TYPE[$request->milk_type]);
            }
             $record = (clone $baseQuery)->first();
            if ($request->isMethod('post')) {
                return Helper::SuccessReturn($record,'DATA_FATECHED');
            } else {
                $size =  UserSettings::getPrintSize($user->user_id);
                $customPaper = array(0, 0, ($size ?? 2) * 72, (6) * 72);
                $pdf = Pdf::loadView('pdf.milk.milk_request', compact('record', 'user'))
                    ->setPaper($customPaper);
                // ->setPaper('A4');
                return $pdf->stream('slip.pdf');
            }
        } else {
            if ($request->isMethod('post')) {
                return Helper::FalseReturn(null, 'INVALID_REQUEST');
            } else {
                flash()->error(__('message.INVALID_REQUEST'));
                return redirect()->route('user.dashboard');
            }
        }
    }

    public function milkBuy(Request $request)
    {
        if ($request->date) {
            $date = $request->input('date');
        } else {
            $date = Carbon::now()->format('Y-m-d');
        }
        if ($request->shift) {
            $mshift = $request->shift == 'morning' ? 'M' : ($request->shift == 'evening' ? 'E' : 'D');
        }
        $user = auth()->user();
        $title = __('lang.Milk Buy Reports');
        $baseQuery =  MilkBuyRecords::query();
        $baseQuery = $baseQuery->where(['buyer_id' => $user->user_id, 'trash' => 0]);
        $baseQuery = $baseQuery->withCostumer();
        $baseQuery =  $baseQuery->whereDate('date', $date);
        if ($request->shift &&  ($request->shift == 'morning' || $request->shift == 'evening')) {
            $baseQuery = $baseQuery->where('shift', $mshift);
        }
        if ($request->milk_type && array_key_exists($request->milk_type, MILK_TYPE)) {
            $baseQuery = $baseQuery->where('milk_type', MILK_TYPE[$request->milk_type]);
        }
        $datas = (clone $baseQuery)->orderby('created_at', 'desc')->paginate(env('PER_PAGE_RECORDS'));


        $quantity = (clone $baseQuery)->sum('quantity');
        $fat = (clone $baseQuery)->sum('fat');
        $snf = (clone $baseQuery)->sum('snf');
        $clr = (clone $baseQuery)->sum('clr');
        $total_price = (clone $baseQuery)->sum('total_price');
        $trashQuery = MilkBuyRecords::query()
            ->where(['buyer_id' => $user->user_id, 'trash' => 1])
            ->whereDate('date', $date);
        if ($request->shift && ($request->shift == 'morning' || $request->shift == 'evening' || $request->shift == 'day')) {
            $trashQuery = $trashQuery->where('shift', $request->shift);
        }
        if ($request->milk_type && array_key_exists($request->milk_type, MILK_TYPE)) {
            $trashQuery = $trashQuery->where('milk_type', MILK_TYPE[$request->milk_type]);
        }
        $trash = $trashQuery->count();
        return view('user.records.milk_buy.index', compact('title', 'datas', 'trash', 'quantity', 'fat', 'snf', 'clr', 'total_price'));
    }
    public function milkBuyPrint(Request $request)
    {
        if ($request->date) {
            $date = $request->input('date');
        } else {
            $date = Carbon::now()->format('Y-m-d');
        }
        if ($request->shift) {
            $mshift = $request->shift == 'morning' ? 'M' : ($request->shift == 'evening' ? 'E' : 'D');
        }
        $user = auth()->user();
        $baseQuery =  MilkBuyRecords::query();
        $baseQuery = $baseQuery->where(['buyer_id' => $user->user_id, 'trash' => 0]);
        $baseQuery =  $baseQuery->whereDate('date', $date);
        $baseQuery = $baseQuery->WithCostumer();
        if ($request->shift &&  ($request->shift == 'morning' || $request->shift == 'evening' || $request->shift == 'day')) {
            $baseQuery = $baseQuery->where('shift', $mshift);
        }
        if ($request->milk_type && array_key_exists($request->milk_type, MILK_TYPE)) {
            $baseQuery = $baseQuery->where('milk_type', MILK_TYPE[$request->milk_type]);
        }
        $datas = (clone $baseQuery)->orderby('created_at', 'desc')->with('seller')->get();
        $shift = ($request->shift) ? $request->shift : 'All';
        $pdf = Pdf::loadView('pdf.milk.milk_buy_records', compact('datas', 'user', 'date', 'shift'))
            ->setPaper('A4');
        $name = 'milk_buy_records' . $date;
        return $pdf->stream($name . '.pdf');
    }
    public function milkBuyTrash(Request $request)
    {
        if ($request->date) {
            $date = $request->input('date');
        } else {
            $date = Carbon::now()->format('Y-m-d');
        }
        $user = auth()->user();
        $title = __('lang.Milk Buy Reports Trash');
        $datas = MilkBuyRecords::where(['buyer_id' => $user->user_id, 'trash' => 1])
            ->WithCostumer()
            ->paginate(env('PER_PAGE_RECORDS'));
        return view('user.records.milk_buy.trash', compact('title', 'datas'));
    }
    public function milkSell(Request $request)
    {
        if ($request->date) {
            $date = $request->input('date');
        } else {
            $date = Carbon::now()->format('Y-m-d');
        }
        if ($request->shift) {
            $mshift = $request->shift == 'morning' ? 'M' : ($request->shift == 'evening' ? 'E' : 'D');
        }
        $user = auth()->user();
        $baseQuery =  MilkSaleRecords::query();
        $baseQuery = $baseQuery->where(['seller_id' => $user->user_id, 'trash' => 0]);
        $baseQuery =  $baseQuery->whereDate('date', $date);
        // shift
        if ($request->shift &&  ($request->shift == 'morning' || $request->shift == 'evening' || $request->shift == 'day')) {
            $baseQuery = $baseQuery->where('shift', $mshift);
        }
        if ($request->milk_type && array_key_exists($request->milk_type, MILK_TYPE)) {
            $baseQuery = $baseQuery->where('milk_type', MILK_TYPE[$request->milk_type]);
        }

        $baseQuery = $baseQuery->WithCostumer();

        $title = __('lang.Milk sale Reports');
        $datas = (clone $baseQuery)->orderby('created_at', 'desc')->paginate(env('PER_PAGE_RECORDS'));
        $quantity = (clone $baseQuery)->sum('quantity');
        $fat = (clone $baseQuery)->sum('fat');
        $snf = (clone $baseQuery)->sum('snf');
        $clr = (clone $baseQuery)->sum('clr');
        $total_price = (clone $baseQuery)->sum('total_price');
        $trashQuery = MilkSaleRecords::query()
            ->where(['buyer_id' => $user->user_id, 'trash' => 1])
            ->whereDate('date', $date);

        if ($request->shift &&  ($request->shift == 'morning' || $request->shift == 'evening' || $request->shift == 'day')) {
            $trashQuery = $trashQuery->where('shift', $request->shift);
        }
        if ($request->milk_type && array_key_exists($request->milk_type, MILK_TYPE)) {
            $trashQuery = $trashQuery->where('milk_type', MILK_TYPE[$request->milk_type]);
        }
        $trash = $trashQuery->count();
        return view('user.records.milk_sell.index', compact('title', 'datas', 'trash', 'quantity', 'fat', 'snf', 'clr', 'total_price'));
    }
    public function milkSellPrint(Request $request)
    {
        if ($request->date) {
            $date = $request->input('date');
        } else {
            $date = Carbon::now()->format('Y-m-d');
        }
        if ($request->shift) {
            $mshift = $request->shift == 'morning' ? 'M' : ($request->shift == 'evening' ? 'E' : 'D');
        }
        $user = auth()->user();
        $baseQuery =  MilkSaleRecords::query();
        $baseQuery = $baseQuery->where(['seller_id' => $user->user_id, 'trash' => 0]);
        $baseQuery =  $baseQuery->whereDate('date', $date);
        // shift
        if ($request->shift &&  ($request->shift == 'morning' || $request->shift == 'evening' || $request->shift == 'day')) {
            $baseQuery = $baseQuery->where('shift', $mshift);
        }
        if ($request->milk_type && array_key_exists($request->milk_type, MILK_TYPE)) {
            $baseQuery = $baseQuery->where('milk_type', MILK_TYPE[$request->milk_type]);
        }
        $baseQuery = $baseQuery->WithCostumer();
        $datas = (clone $baseQuery)->orderby('created_at', 'desc')->get();
        $shift = ($request->shift) ? $request->shift : 'All';

        $pdf = Pdf::loadView('pdf.milk.milk_sell_records', compact('datas', 'user', 'date', 'shift'))
            ->setPaper('A4');
        $name = 'milk_buy_records' . $date;
        return $pdf->stream($name . '.pdf');
    }
    public function milkSellTrash(Request $request)
    {
        if ($request->date) {
            $date = $request->input('date');
        } else {
            $date = Carbon::now()->format('Y-m-d');
        }
        $user = auth()->user();
        $title = __('lang.Milk sale Reports Trash');
        $datas = MilkSaleRecords::where(['seller_id' => $user->user_id, 'trash' => 1])
            ->WithCostumer()->paginate(env('PER_PAGE_RECORDS'));
        return view('user.records.milk_sell.trash', compact('title', 'datas'));
    }
}
