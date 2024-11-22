<?php

namespace App\Http\Controllers\Api\Transport;

use App\Helper\Helper;
use App\Models\Routes;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RouteController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $routes = Routes::where(['transporter_id' => $user->user_id, 'parent_id' => $user->parent_id, 'deleted' => false])
            ->with('driver')
            ->orderby('route_id', 'desc')->paginate(env('PER_PAGE_RECORDS'));
        $totalPage = $routes->lastPage();
        $nextPage = $routes->nextPageUrl();
        $data = $routes->items();
        return Helper::SuccessReturnPagination($data, $totalPage, $nextPage, 'Routes list fetched successfully.');
    }
    public function edit(Request $request)
    {
        $user = $request->user();
        $rules = [
            'route_id' => ['required', Rule::exists('routes', 'route_id')->where(function ($query) use ($user) {
                $query->where('transporter_id', $user->transporter_id)
                    ->where('deleted', false);
            })],
            'driver_id' => [
                'required', Rule::exists('transport_drivers', 'driver_id')->where(function ($query) use ($user) {
                    $query->where('transporter_id', $user->transporter_id)
                        ->where('deleted', false);
                })
            ],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        return $request;
    }
}
