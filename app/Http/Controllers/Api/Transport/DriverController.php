<?php

namespace App\Http\Controllers\Api\Transport;

use App\Helper\Helper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\TransportDrivers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $datas = TransportDrivers::where(['transporter_id' => $user->transporter_id, 'deleted' => false])->orderby('id', 'desc')->paginate(env('PER_PAGE_RECORDS'));
        $totalPage = $datas->lastPage();
        $nextPage = $datas->nextPageUrl();
        $data = $datas->items();
        return Helper::SuccessReturnPagination($data, $totalPage, $nextPage, 'Driver list fetched successfully.');
    }
    public function add(Request $request)
    {
        $user = $request->user();
        $rules = [
            'name' => ['required', 'string', 'max:100'],
            'father_name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', Rule::unique('transport_drivers', 'email')],
            'country_code' => ['required'],
            'mobile' => ['required', 'numeric', Rule::when(function () {
                return in_array(request()->input('country_code'), ['91', '+91']);
            }, 'regex:/^[6789]\d{9}$/'), Rule::unique('transport_drivers', 'mobile')],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $data = $request->only('name', 'father_name', 'email', 'country_code', 'mobile');
        $data['password'] = bcrypt('123456');
        $data['transporter_id'] = $user->transporter_id;
        $createdDriver = TransportDrivers::create($data);
        if ($createdDriver) {
            return Helper::SuccessReturn($createdDriver, 'New Driver added successfully.');
        } else {
            return Helper::FalseReturn(null, 'SOMETHING_WENT_WRONG');
        }
    }
    public function update(Request $request)
    {
        $user = $request->user();
        $rules = [
            'driver_id' => ['required', Rule::exists('transport_drivers', 'driver_id')->where(function ($query) use ($user) {
                $query->where('transporter_id', $user->transporter_id)
                    ->where('deleted', false);
            }),],
            'name' => ['required', 'string', 'max:100'],
            'father_name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', Rule::unique('transport_drivers', 'email')->ignore($request->driver_id, 'driver_id')],
            'country_code' => ['required'],
            'mobile' => ['required', 'numeric', Rule::when(function () {
                return in_array(request()->input('country_code'), ['91', '+91']);
            }, 'regex:/^[6789]\d{9}$/'), Rule::unique('transport_drivers', 'mobile')->ignore($request->driver_id, 'driver_id')],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $data = $request->only('name', 'father_name', 'email', 'country_code', 'mobile');
        $driver = TransportDrivers::where(['driver_id' => $request->driver_id, 'transporter_id' => $user->transporter_id])->first();
        if ($driver->update($data)) {
            return Helper::SuccessReturn($driver, 'Driver updated successfully.');
        } else {
            return Helper::FalseReturn(null, 'SOMETHING_WENT_WRONG');
        }
    }
    public function statusChange(Request $request)
    {
        $user = auth()->user();
        $rules = [
            'driver_id' => ['required', Rule::exists('transport_drivers', 'driver_id')->where(function ($query) use ($user) {
                $query->where('transporter_id', $user->transporter_id)
                    ->where('deleted', false);
            }),],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $data = TransportDrivers::where(['driver_id' => $request->input('driver_id')])->first();
        $data->is_blocked = ($data->is_blocked) ? false : true;
        $data->update();
        return Helper::SuccessReturn(null, 'Driver status changed successfully.');
    }
    public function delete(Request $request)
    {
        $user = auth()->user();
        $rules = [
            'driver_id' => ['required', Rule::exists('transport_drivers', 'driver_id')->where(function ($query) use ($user) {
                $query->where('transporter_id', $user->transporter_id)
                    ->where('deleted', false);
            }),],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $data = TransportDrivers::where(['driver_id' => $request->input('driver_id')])->first();
        $data->deleted = true;
        $data->update();
        return Helper::SuccessReturn(null, 'Driver deleted successfully.');
    }
}
