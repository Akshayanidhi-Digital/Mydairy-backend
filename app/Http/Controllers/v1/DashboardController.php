<?php

namespace App\Http\Controllers\v1;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Buyers;
use App\Models\Farmer;
use App\Models\MessagesAlert;
use App\Models\MilkBuyRecords;
use App\Models\MilkSaleRecords;
use App\Models\MilkTransportRecords;
use App\Models\Products;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public $user;

    public function __construct(Request $request)
    {
        $this->user = auth()->user();
    }


    public function index()
    {
        $title = __('constants.dashboard');
        $user = auth()->user();
        $cards = [
            ['id' => 1, 'title' => __('lang.Total Milk'), 'class' => 'card-dark-blue', 'subtitle' => 'total_milk'],
            ['id' => 2, 'title' => __('lang.Today Milk'), 'class' => 'card-light-blue', 'subtitle' => 'today_milk'],
            ['id' => 3, 'title' => __('lang.Total Buy Milk'), 'class' => 'card-dark-blue', 'subtitle' => 'total_buy_milk'],
            ['id' => 4, 'title' => __('lang.Today Buy Milk'), 'class' => 'card-light-blue', 'subtitle' => 'today_buy_milk'],
            ['id' => 5, 'title' => __('lang.Total Sold Milk'), 'class' => 'card-dark-blue', 'subtitle' => 'total_sold_milk'],
            ['id' => 6, 'title' => __('lang.Today Sold Milk'), 'class' => 'card-light-blue', 'subtitle' => 'today_sold_milk'],
            ['id' => 7, 'title' => __('lang.Total Farmers'), 'class' => 'card-tale', 'subtitle' => 'total_farmers'],
            ['id' => 8, 'title' => __('lang.Total Buyers'), 'class' => 'card-tale', 'subtitle' => 'total_buyers'],
            ['id' => 9, 'title' => __('lang.In-active Farmers'), 'class' => 'card-light-danger', 'subtitle' => 'inactive_farmers'],
            ['id' => 10, 'title' => __('lang.In-active Buyers'), 'class' => 'card-light-danger', 'subtitle' => 'inactive_buyers'],
            ['id' => 11, 'title' => __('lang.Total Products'), 'class' => 'card-dark-blue', 'subtitle' => 'total_products'],
        ];
        $date = Carbon::now();
        $total_buy_milk = MilkBuyRecords::where(['buyer_id' => $user->user_id, 'trash' => 0])->sum('quantity');
        $today_buy_milk = MilkBuyRecords::where(['buyer_id' => $user->user_id, 'trash' => 0])->whereDate('date', '=', $date)->sum('quantity');
        $total_sold_milk = MilkSaleRecords::where(['seller_id' => $user->user_id, 'trash' => 0])->sum('quantity');
        $today_sold_milk = MilkSaleRecords::where(['seller_id' => $user->user_id, 'trash' => 0])->whereDate('date', '=', $date)->sum('quantity');
        $total_farmers = Farmer::where(['parent_id' => $user->user_id, 'trash' => 0])->count();
        $total_buyers = Buyers::where(['parent_id' => $user->user_id, 'trash' => 0])->count();
        $inactive_farmers = Farmer::where(['parent_id' => $user->user_id, 'trash' => 1])->count();
        $inactive_buyers = Buyers::where(['parent_id' => $user->user_id, 'trash' => 1])->count();
        $total_products = Products::where(['user_id' => $user->user_id, 'trash' => 0])->count();
        $total_milk = $total_buy_milk - $total_sold_milk;
        $today_milk = $today_buy_milk - $today_sold_milk;
        return view('user.home', compact('title', 'cards', 'total_milk', 'today_milk', 'total_buy_milk', 'today_buy_milk', 'total_sold_milk', 'today_sold_milk', 'total_farmers', 'inactive_farmers', 'total_buyers', 'inactive_buyers', 'total_products'));
    }

    public function notification()
    {
        $user = auth()->user();
        $datas = MessagesAlert::where(['user_id' => $user->user_id])
            ->orderby('id', 'desc')
            ->paginate(env('PER_PAGE_RECORDS'));
        $title = 'Notifications';
        // $recordIds = $datas->filter(function ($message) {
        //     return $message->message_type == 2;
        // })->pluck('record_id');
        // $milkBuyRecords = MilkBuyRecords::whereIn('id', $recordIds)->get();
        // $datas->transform(function ($message) use ($milkBuyRecords) {
        //     if ($message->message_type == 2) {
        //         $message->milk_detail = $milkBuyRecords->firstWhere('id', $message->record_id);
        //     }
        //     return $message;
        // });
        return view('user.notification.index', compact('title', 'datas'));
    }
    public function notificationDelete(Request $request)
    {
        $user = auth()->user();
        $rules = [
            'record_id' => ['required', Rule::exists('messages_alerts', 'id')->where(function ($query) use ($user) {
                $query->where(['user_id' => $user->user_id]);
            })]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn([], $validator->errors()->first());
        }
        MessagesAlert::where(['id' => $request->record_id, 'user_id' => $user->user_id])
            ->delete();
        return Helper::SuccessReturn(null, 'Message deleted successfully.');
    }
    public function notificationMilkData(Request $request)
    {
        $user = auth()->user();
        $rules = [
            'record' => ['required', 'numeric', Rule::exists('messages_alerts', 'id')->where(function ($query) use ($user) {
                $query->where(['user_id' => $user->user_id])->whereIn('message_type', [2, 3]);
            })],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn([], $validator->errors()->first());
        }
        $msg =  MessagesAlert::where(['id' => $request->record, 'user_id' => $user->user_id])->first();
        if ($msg->message_type == 2) {
            $data = MilkBuyRecords::where(['id' => $msg->record_id, 'seller_id' => $user->user_id])->first();
        } else {
            $data = [];
        }
        return Helper::SuccessReturn($data, 'DATA_FETCHED');
    }
    public function notificationMilkAction(Request $request)
    {
        $user = auth()->user();
        $rules = [
            'is_accept' => ['required', 'in:true,false'],
            'is_transport' => ['required', 'in:true,false'],
            'record' => ['required', 'numeric', Rule::exists('messages_alerts', 'id')->where(function ($query) use ($user) {
                $query->where(['user_id' => $user->user_id])
                    ->whereIn('message_type', [2, 3]);
            })],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn([], $validator->errors()->first());
        }
        $msg =  MessagesAlert::where(['id' => $request->record, 'user_id' => $user->user_id])->first();
        $msg->is_marked = true;
        $msg->update();
        if ($msg->message_type == 2) {
            $data = MilkBuyRecords::where(['id' => $msg->record_id, 'seller_id' => $user->user_id])->first();
        } else {
            $data = [];
        }
        if ($request->is_accept == 'true') {
            $data->update([
                'is_accepted' => true,
            ]);
            $data2 = MilkTransportRecords::where(['record_id' => $data->id])->update(['is_transport' => ($request->is_transport == 'true') ? true : false]);
        } else {
            $data->update([
                'is_accepted' => false,
            ]);
        }
        return Helper::SuccessReturn(null, 'Milk request updated successfully.');
    }
}
