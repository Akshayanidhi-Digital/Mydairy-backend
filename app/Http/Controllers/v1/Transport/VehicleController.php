<?php

namespace App\Http\Controllers\v1\Transport;

use App\Helper\Helper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\TransportDrivers;
use App\Models\TransportVehicle;
use App\Http\Controllers\Controller;
use App\Rules\ValidDriverForVehicle;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    public function index()
    {
        $title = 'Vechile List';
        $user = auth()->user();
        $datas = TransportVehicle::where(['transporter_id' => $user->transporter_id])->with('driver')->has('driver')->paginate(env('PER_PAGE_RECORDS'));
        return view('transport.vehicle.index', compact('title', 'datas'));
    }
    public function add()
    {
        $title = 'Vechile Add';
        $user = auth()->user();
        $drivers = TransportDrivers::where(['transporter_id' => $user->transporter_id, 'deleted' => false])
            ->whereDoesntHave('vehicle')
            ->get();
        return view('transport.vehicle.add', compact('title', 'user', 'drivers'));
    }
    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            "driver" => ['required'],
            "vehicle_number" => ['required', 'regex:/^[A-Za-z]{2}[ -][0-9]{1,2}(?: [A-Za-z])?(?: [A-Za-z]*)? [0-9]{4}$/'],
            "quantity" => ['required', 'regex:/^\d+(\.\d)?$/'],
            "unit_type" => ['required', Rule::in(VEHICLE_UNIT)]
        ]);
        $data =  $request->only('vehicle_number', 'unit_type');
        $data['vehicle_number'] = strtoupper($data['vehicle_number']);
        $data['transporter_id'] = $user->transporter_id;
        $data['driver_id'] = $request->driver;
        $data['capacity'] = $request->quantity;
        if (TransportVehicle::create($data)) {
            return redirect()->route('transport.vehicle.index')->with('success', 'new Vehicle add successfully.');
        } else {
            return redirect()->back()->withInput($request->except('_token'))->with('error', __('message.SOMETHING_WENT_WRONG'));
        }
    }
    public function edit($id)
    {
        $user = auth()->user();
        $title = 'Vechile Edit';
        $vehicle = TransportVehicle::where(['transporter_id' => $user->transporter_id, 'id' => $id])->first();
        if (!$vehicle) {
            return redirect()->route('transport.vehicle.index')->with('error', 'Invalid details. please recheck');
        }
        $drivers = TransportDrivers::where(['transporter_id' => $user->transporter_id, 'deleted' => false])
            ->whereDoesntHave('vehicle')
            ->orwhere('driver_id', $vehicle->driver_id)
            ->select('name', 'driver_id')
            ->get();
        return view('transport.vehicle.edit', compact('title', 'user', 'drivers', 'vehicle'));
    }
    public function update($id, Request $request)
    {
        $user = auth()->user();
        $vehicle = TransportVehicle::where(['transporter_id' => $user->transporter_id, 'id' => $id])->first();
        if (!$vehicle) {
            return redirect()->route('transport.vehicle.index')->with('error', 'Invalid details. please recheck');
        }
        $request->validate([
            "driver" => ['required', new ValidDriverForVehicle($user->transporter_id, $vehicle->driver_id)],
            "vehicle_number" => ['required', 'regex:/^[A-Za-z]{2}[ -][0-9]{1,2}(?: [A-Za-z])?(?: [A-Za-z]*)? [0-9]{4}$/'],
            "quantity" => ['required', 'regex:/^\d+(\.\d)?$/'],
            "unit_type" => ['required', Rule::in(VEHICLE_UNIT)]
        ]);
        $data =  $request->only('unit_type');
        $data['driver_id'] = $request->input('driver',$vehicle->driver_id);
        $data['capacity'] = $request->quantity;
        if ($vehicle->update($data)) {
            return redirect()->route('transport.vehicle.index')->with('success','Vehicle updated successfully.');
        } else {
            return redirect()->back()->withInput($request->except('_token'))->with('error', __('message.SOMETHING_WENT_WRONG'));
        }
    }
    public function updateStatus(Request $request){
        $rules = [
            "vehicle" => ['required',Rule::exists('transport_vehicles','id')],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $user = $request->user();
        $vehicle = TransportVehicle::where(['id' => $request->vehicle, 'transporter_id' => $user->transporter_id])->first();
        $vehicle->is_active = ($vehicle->is_active) ? false : true;
        $vehicle->update();
        return Helper::SuccessReturn(null, 'vehicle status changed successfully.');
    }
    public function delete(Request $request){
        $rules = [
            "vehicle" => ['required',Rule::exists('transport_vehicles','id')],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $user = $request->user();
        $vehicle = TransportVehicle::where(['id' => $request->vehicle, 'transporter_id' => $user->transporter_id])->first();
        $vehicle->delete();
        return Helper::SuccessReturn(null, 'vehicle deleted successfully.');
    }
}
