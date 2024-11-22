<?php

namespace App\Http\Controllers\v1\Transport;

use App\Helper\Helper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\TransportDrivers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $title = 'Driver List';
        $datas = TransportDrivers::where(['transporter_id' => $user->transporter_id, 'deleted' => false])->paginate(env('PER_PAGE_RECORDS'));
        return view('transport.driver.index', compact('title', 'datas'));
    }
    public function add()
    {
        $title = 'Driver Add';
        return view('transport.driver.add', compact('title'));
    }
    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'father_name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', Rule::unique('transport_drivers', 'email')],
            'country_code' => ['required'],
            'mobile' => ['required', 'numeric', Rule::when(function () {
                return in_array(request()->input('country_code'), ['91', '+91']);
            }, 'regex:/^[6789]\d{9}$/'), Rule::unique('transport_drivers', 'mobile')],
        ]);

        $data = $request->only('name', 'father_name', 'email', 'country_code', 'mobile');
        $data['password'] = bcrypt('123456'); // Generate password and send text message
        $data['transporter_id'] = $user->transporter_id;
        if (TransportDrivers::create($data)) {
            return redirect()->route('transport.driver.index')->with('success', 'new Driver added successfully.');
        } else {
            return redirect()->back()->with('error', __('message.SOMETHING_WENT_WRONG'))->withInput($request->only('name', 'father_name', 'email', 'country_code', 'mobile'));
        }
    }
    public function updateStatus(Request $request)
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
    public function edit($id)
    {
        $user = auth()->user();
        $title = 'Driver Edit';
        $driver = TransportDrivers::where(['driver_id' => $id, 'transporter_id' => $user->transporter_id])->first();
        if (!$driver) {
            return redirect()->route('transport.driver.index')->with('error', 'Driver not found.');
        }
        return view('transport.driver.edit', compact('title', 'driver'));
    }
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'father_name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', Rule::unique('transport_drivers', 'email')->ignore($id, 'driver_id')],
            'country_code' => ['required'],
            'mobile' => ['required', 'numeric', Rule::when(function () {
                return in_array(request()->input('country_code'), ['91', '+91']);
            }, 'regex:/^[6789]\d{9}$/'), Rule::unique('transport_drivers', 'mobile')->ignore($id, 'driver_id')],
        ]);
        $driver = TransportDrivers::where(['driver_id' => $id, 'transporter_id' => $user->transporter_id])->first();
        if (!$driver) {
            return redirect()->route('transport.driver.index')->with('error', 'Driver not found.');
        }
        $data = $request->only('name', 'father_name', 'email', 'country_code', 'mobile');
        if ($driver->update($data)) {
            return redirect()->route('transport.driver.index')->with('success', 'Driver updated successfully.');
        } else {
            return redirect()->back()->with('error', __('message.SOMETHING_WENT_WRONG'))->withInput($request->only('name', 'father_name', 'email', 'country_code', 'mobile'));
        }
    }
}
