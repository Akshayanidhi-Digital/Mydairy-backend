<?php

namespace App\Http\Controllers\Api\Transport;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\TransportDrivers;
use App\Models\TransportVehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $vehicle = TransportVehicle::where(['transporter_id' => $user->transporter_id])->with('driver')->has('driver')->paginate(env('PER_PAGE_RECORDS'));
        $totalPage = $vehicle->lastPage(); // Total number of pages
        $nextPage = $vehicle->nextPageUrl(); // URL for the next page
        $data = $vehicle->items(); // Actual data for the current page
        return Helper::SuccessReturnPagination($data, $totalPage, $nextPage, 'Vehicle list fetched successfully.');
    }
    public function add(Request $request)
    {
        $user = $request->user();
        $rules = [
            "driver" => ['required',Rule::exists('transport_drivers','driver_id'),Rule::unique('transport_vehicles','driver_id')],
            "vehicle_number" => ['required', 'regex:/^[A-Za-z]{2}[ -][0-9]{1,2}(?: [A-Za-z])?(?: [A-Za-z]*)? [0-9]{4}$/',Rule::unique('transport_vehicles','vehicle_number')],
            "quantity" => ['required', 'regex:/^\d+(\.\d)?$/'],
            "unit_type" => ['required', Rule::in(VEHICLE_UNIT)],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $data =  $request->only('vehicle_number', 'unit_type');
        $data['vehicle_number'] = strtoupper($data['vehicle_number']);
        $data['transporter_id'] = $user->transporter_id;
        $data['driver_id'] = $request->driver;
        $data['capacity'] = $request->quantity;
        $res = TransportVehicle::create($data);
        if ($res) {
            return Helper::SuccessReturn($res, 'new Vehicle add successfully.');
        } else {
            return Helper::FalseReturn(null, __('message.SOMETHING_WENT_WRONG'));
        }
    }

    public function update(Request $request)
    {
        $rules = [
            "vehicle" => ['required',Rule::exists('transport_vehicles','id')],
            "driver" => ['required',Rule::exists('transport_drivers','driver_id'),Rule::unique('transport_vehicles','driver_id')->ignore($request->vehicle)],
            "quantity" => ['required', 'regex:/^\d+(\.\d)?$/'],
            "unit_type" => ['required', Rule::in(VEHICLE_UNIT)],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $user = $request->user();
        $vehicle = TransportVehicle::where(['id' => $request->vehicle, 'transporter_id' => $user->transporter_id])->first();
        $data =  $request->only('unit_type');
        $data['driver_id'] = $request->input('driver',$vehicle->driver_id);
        $data['capacity'] = $request->quantity;
        if ($vehicle->update($data)) {
            return Helper::SuccessReturn($vehicle, 'Vehicle updated successfully.');
        } else {
            return Helper::FalseReturn(null, 'SOMETHING_WENT_WRONG');
        }
    }
    public function statusChange(Request $request){
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
    public function drivers(Request $request){
        $user = $request->user();
        $drivers = TransportDrivers::where(['transporter_id' => $user->transporter_id, 'deleted' => false])
        ->whereDoesntHave('vehicle')
        ->select("driver_id",'name','father_name')
        ->get();
        return Helper::SuccessReturn($drivers,'Data fatched successfully.');
    }
}
