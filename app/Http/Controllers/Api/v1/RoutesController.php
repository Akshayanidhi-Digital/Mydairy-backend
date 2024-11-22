<?php

namespace App\Http\Controllers\Api\v1;

use App\Helper\Helper;
use App\Models\Routes;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Middleware\IsDairyMiddleware;
use App\Models\RoutesDairyList;
use App\Models\User;

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
        return $this->getList($user->user_id, 'ROUTES_LIST_FETCHED');
    }
    protected function getList($parent_id, $message)
    {
        $data = Routes::where(['parent_id' => $parent_id, 'trash' => false, 'deleted' => false])->with('dairies')->orderby('route_id', 'desc')->get();
        return Helper::StatusReturn($data, $message);
    }
    public function dairy_list(Request $request)
    {
        $user = $request->user();
        $data = User::where(['parent_id' => $user->user_id, 'is_blocked' => false])->select('user_id', 'name')->get();
        return Helper::SuccessReturn($data, 'DATA_FETCHED');
    }

    public function add(Request $request)
    {
        $user = $request->user();
        $rules = [
            'route_name' => ['required', 'string'],
            'dairy_list' => ['required', 'array'],
            'dairy_list.*' => ['required', 'string', Rule::exists('users', 'user_id')->where(function ($query) use ($user) {
                $query->where('parent_id', $user->user_id);
            })],
            'transporter_id' => ['nullable', Rule::exists('transporters', 'transporter_id')->where(function ($query) use ($user) {
                $query->where(['parent_id' => $user->user_id, 'deleted' => false]);
            })]
        ];
        $messages = [
            'dairy_list.array' => 'The dairy list must be an array.',
            'dairy_list.*.required' => 'Dairy list is required.',
            'dairy_list.*.string' => 'All dairy list must be a string.',
            'dairy_list.*.exists' => 'Selected dairy list are invalid.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $route = new Routes();
        $route->parent_id = $user->user_id;
        $route->route_name = $request->route_name;
        $route->is_assigned = $request->has('transporter_id') ? true : false;
        $route->transporter_id = $request->input('transporter_id', null);
        $route->save();
        foreach ($request->dairy_list as $data) {
            $dairy = new RoutesDairyList();
            $dairy->route_id = $route->route_id;
            $dairy->parent_id = $route->parent_id;
            $dairy->dairy_id = $data;
            $dairy->save();
        }
        return $this->getList($user->user_id, 'ROUTE_ADDED_SUCCESSFULLY');
    }
    public function update(Request $request)
    {
        $user = $request->user();

        $rules = [
            'route_id' => ['required', 'string', Rule::exists('routes', 'route_id')->where(function ($query) use ($user) {
                $query->where('parent_id', $user->user_id);
            })],
            'route_name' => ['required', 'string'],
            'dairy_list' => ['required', 'array'],
            'dairy_list.*' => ['required', 'string', Rule::exists('users', 'user_id')->where(function ($query) use ($user) {
                $query->where('parent_id', $user->user_id);
            })],
            'transporter_id' => ['nullable', Rule::exists('transporters', 'transporter_id')->where(function ($query) use ($user) {
                $query->where(['parent_id' => $user->user_id, 'deleted' => false]);
            })]
        ];
        $messages = [
            'dairy_list.array' => 'The dairy list must be an array.',
            'dairy_list.*.required' => 'Dairy list is required.',
            'dairy_list.*.string' => 'All dairy list must be a string.',
            'dairy_list.*.exists' => 'Selected dairy list are invalid.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $route = Routes::where(['route_id' => $request->route_id, 'parent_id' => $user->user_id])->first();
        $route->parent_id = $user->user_id;
        $route->route_name = $request->route_name;
        $route->is_assigned = $request->has('transporter_id') ? true : false;
        $route->transporter_id = $request->input('transporter_id', $route->transporter_id);
        $route->save();
        RoutesDairyList::where(['route_id' => $request->route_id, 'parent_id' => $user->user_id])->delete();
        foreach ($request->dairy_list as $data) {
            $dairy['route_id'] = $route->route_id;
            $dairy['parent_id'] = $route->parent_id;
            $dairy["dairy_id"] = $data;
            RoutesDairyList::updateOrCreate($dairy, $dairy);
        }
        return $this->getList($user->user_id, 'ROUTE_UPDATED_SUCCESSFULLY');
    }
    public function statusUpdate(Request $request)
    {
        $user = $request->user();

        $rules = [
            'route_id' => ['required', 'string', Rule::exists('routes', 'route_id')->where(function ($query) use ($user) {
                $query->where('parent_id', $user->user_id);
            })],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $route = Routes::where(['route_id' => $request->route_id, 'parent_id' => $user->user_id])->first();
        $route->trash = ($route->trash) ? false : true;
        $route->update();
        return Helper::EmptyReturn('ROUTE_STATUS_SUCCESSFULLY');
    }
    public function delete(Request $request)
    {
        $user = $request->user();

        $rules = [
            'route_id' => ['required', 'string', Rule::exists('routes', 'route_id')->where(function ($query) use ($user) {
                $query->where('parent_id', $user->user_id);
            })],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $route = Routes::where(['route_id' => $request->route_id, 'parent_id' => $user->user_id])->first();
        $route->update(['deleted' => true]);
        return Helper::EmptyReturn('ROUTE_DELETED_SUCCESSFULLY');
    }
}
