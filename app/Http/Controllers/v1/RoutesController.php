<?php

namespace App\Http\Controllers\v1;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Middleware\IsDairyMiddleware;
use App\Models\Routes;
use App\Models\RoutesDairyList;
use App\Models\Transporters;
use App\Models\User;
use App\Notifications\FirebaseMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RoutesController extends Controller
{
    public static function middleware(): array
    {
        return [
            IsDairyMiddleware::class
        ];
    }
    public function index(Request $request)
    {
        $user = $request->user();
        $datas = Routes::where(['parent_id' => $user->user_id, 'trash' => false, 'deleted' => false])->with('transporter')->orderby('route_id', 'desc')->paginate(env('PER_PAGE_RECORDS'));
        $title = __('lang.Routes');
        return view('user.masters.route.index', compact('title', 'datas'));
    }
    protected function getList($parent_id, $message)
    {
        $data = Routes::where(['parent_id' => $parent_id, 'trash' => false, 'deleted' => false])->with('dairies')->orderby('route_id', 'desc')->get();
        return Helper::StatusReturn($data, $message);
    }
    public function add()
    {
        $title = __('lang.Add :name', ['name' => __('lang.Route')]);
        $user = request()->user();
        $dairy_list = User::where(['parent_id' => $user->user_id, 'is_blocked' => false])->select('user_id', 'name', 'role', 'role_id')->get();
        $transporter = Transporters::where(['parent_id' => $user->user_id, 'is_blocked' => false, 'deleted' => false])->get();
        return view('user.masters.route.add', compact('title', 'dairy_list', 'transporter'));
    }
    public function store(Request $request)
    {
        $user = $request->user();
        $rules = [
            'route_name' => ['required', 'string'],
            'dairy_list' => ['required', 'array'],
            'dairy_list.*' => ['required', 'string', Rule::exists('users', 'user_id')->where(function ($query) use ($user) {
                $query->where('parent_id', $user->user_id);
            })],
            'transporter' => ['nullable', Rule::exists('transporters', 'transporter_id')->where(function ($query) use ($user) {
                $query->where(['parent_id' => $user->user_id, 'deleted' => false]);
            })]
        ];
        $messages = [
            'dairy_list.array' => 'The dairy list must be an array.',
            'dairy_list.*.required' => 'Dairy list is required.',
            'dairy_list.*.string' => 'All dairy list must be a string.',
            'dairy_list.*.exists' => 'Selected dairy list are invalid.',
        ];
        $request->validate($rules, $messages);
        $route = new Routes();
        $route->parent_id = $user->user_id;
        $route->route_name = $request->route_name;
        $route->is_assigned = $request->has('transporter') ? true : false;
        $route->transporter_id = $request->input('transporter', null);
        $route->save();
        foreach ($request->dairy_list as $data) {
            User::updateRouteID($data, $route->route_id);
            $dairy = new RoutesDairyList();
            $dairy->route_id = $route->route_id;
            $dairy->parent_id = $route->parent_id;
            $dairy->dairy_id = $data;
            $dairy->save();
        }
        return redirect()->route('user.masters.routes.list')->with('success', __('message.ROUTE_ADDED_SUCCESSFULLY'));
    }
    public function edit(Request $request, $route_id)
    {
        $title = __('lang.:name Edit', ['name' => __('lang.Route')]);
        $user = $request->user();
        $route = Routes::where(['route_id' => $route_id, 'parent_id' => $user->user_id, 'trash' => false, 'deleted' => false])->with('dairies')->first();
        $dairy_list = User::where(['parent_id' => $user->user_id, 'is_blocked' => false])->select('user_id', 'name', 'role', 'role_id')->get();
        $transporter = Transporters::where(['parent_id' => $user->user_id, 'is_blocked' => false, 'deleted' => false])->get();

        return view('user.masters.route.edit', compact('title', 'route', 'dairy_list', 'transporter'));
    }
    public function update(Request $request, $route_id)
    {
        $user = $request->user();
        $rules = [
            'route_name' => ['required', 'string'],
            'dairy_list' => ['required', 'array'],
            'dairy_list.*' => ['required', 'string', Rule::exists('users', 'user_id')->where(function ($query) use ($user) {
                $query->where('parent_id', $user->user_id);
            })],
            'transporter' => ['nullable', Rule::exists('transporters', 'transporter_id')->where(function ($query) use ($user) {
                $query->where(['parent_id' => $user->user_id, 'deleted' => false]);
            })]
        ];
        $messages = [
            'dairy_list.array' => 'The dairy list must be an array.',
            'dairy_list.*.required' => 'Dairy list is required.',
            'dairy_list.*.string' => 'All dairy list must be a string.',
            'dairy_list.*.exists' => 'Selected dairy list are invalid.',
        ];
        $request->validate($rules, $messages);
        $route = Routes::where(['route_id' => $route_id, 'parent_id' => $user->user_id])->with('transporter')->first();
        if (!$route) {
            return redirect()->back()->with('error', __('message.ROUTE_NOT_FOUND'));
        }
        if ($route->transporter_id == $request->input('transporter')) {
            $token = $route->transporter->fcm_token;
            $message = 'Your route have new updates.';
        } else {
            $token = Transporters::where('transporter_id', $request->input('transporter'))->first()->fcm_token;
            $message = 'Your have new route.';
        }
        $route->parent_id = $user->user_id;
        $route->route_name = $request->route_name;
        $route->is_assigned = $request->has('transporter') ? true : false;
        $route->transporter_id = $request->input('transporter', $route->transporter_id);
        if ($token) {
            Log::info('Message:', ['message' => $message]);
            $data =  [
                "image" => 'https://mydairy.tech/assets/ecom/images/web_banner.png',
                "message" => $message,
                "remark" => "Just for testing",
            ];
            FirebaseMessage::sendNotificationWithImageData($token, $message, 'https://mydairy.tech/assets/ecom/images/web_banner.png', $data);
        }
        $route->update();
        RoutesDairyList::where(['route_id' => $request->route_id, 'parent_id' => $user->user_id])->delete();
        foreach ($request->dairy_list as $data) {
            User::updateRouteID($data, $route->route_id);
            $dairy['route_id'] = $route->route_id;
            $dairy['parent_id'] = $route->parent_id;
            $dairy["dairy_id"] = $data;
            RoutesDairyList::updateOrCreate($dairy, $dairy);
        }
        return redirect()->route('user.masters.routes.list')->with('success', __('message.ROUTE_UPDATED_SUCCESSFULLY'));
    }
    public function statusUpdate(Request $request)
    {
        return $request;
    }
    public function delete(Request $request)
    {
        return $request;
    }
}
