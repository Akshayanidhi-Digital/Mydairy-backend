<?php

namespace App\Http\Controllers\Api\v1;

use App\Exports\MilkRateExport;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Imports\MilkRateImport;
use App\Models\MilkRateChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class MilkRateChartController extends Controller
{

    public function sellRateChart(Request $request)
    {
        $rules = [
            'milk_type' => ['required', "in:Cow,Buffalo,Mix,Other"],

        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $user = $request->user();
        $data = MilkRateChart::where(['chart_type' => 'Sell', 'user_id' => $user->user_id, 'milk_type' => $request->input('milk_type')])->select('fat', 'snf', 'rate')->get();
        if ($data->count() == 0) {
            $data = MilkRateChart::where(['chart_type' => 'Sell', 'user_id' => 'DEMO'])->select('fat', 'snf', 'rate')->get();
        }
        if($data->count() == 0){
            return Helper::FalseReturn(null,'NO_RATE_CHART');
        }

        $fats = $data->pluck('fat')->unique()->values();
        $snfs = $data->pluck('snf')->unique()->values();
        $rateData = [];

        foreach ($fats as $key1 => $fat) {
            $rates = [];
            foreach ($snfs as $key2 => $snf) {
                $rate = $this->getRateChart($data, $fat, $snf);
                $rates[] = $rate;
            }
            $rateData[] = $rates;
        }
        $newdata = [
            'fat' => $fats,
            'snf' => $snfs,
            'rate' => $rateData,
        ];
        return Helper::SuccessReturn($newdata, 'RATE_CHART_FETCHED');
    }
    private function getRateChart($data, $fat, $snf)
    {
        foreach ($data as $innerArray) {
            if ($innerArray['fat'] === $fat && $innerArray['snf']  == $snf) {
                return $innerArray['rate'];
            }
        }
    }
    public function buyRateChart(Request $request)
    {
        $rules = [
            'milk_type' => ['required', "in:Cow,Buffalo,Mix,Other"],

        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $user = $request->user();
        $data = MilkRateChart::where(['chart_type' => 'Purchase', 'user_id' => $user->user_id, 'milk_type' => $request->input('milk_type')])->select('fat', 'snf', 'rate')->get();
        if ($data->count() == 0) {
            $data = MilkRateChart::where(['chart_type' => 'Purchase', 'user_id' => 'DEMO'])->select('fat', 'snf', 'rate')->get();
        }

        $fats = $data->pluck('fat')->unique()->values();
        $snfs = $data->pluck('snf')->unique()->values();
        $rateData = [];

        foreach ($fats as $key1 => $fat) {
            $rates = [];
            foreach ($snfs as $key2 => $snf) {
                $rate = $this->getRateChart($data, $fat, $snf);
                $rates[] = $rate;
            }
            $rateData[] = $rates;
        }
        $newdata = [
            'fat' => $fats,
            'snf' => $snfs,
            'rate' => $rateData,
        ];
        return Helper::SuccessReturn($newdata, 'RATE_CHART_FETCHED');
    }
    public function index(Request $request){
        $rules = [
            'rate_type' => ['required', 'in:' . implode(',', array_keys(RATE_CHART_TYPE))],
            'milk_type' => ['required', 'in:Cow,Buffalo,Mix,Other'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $user = $request->user();
        $data = MilkRateChart::where(['chart_type' => $request->rate_type, 'user_id' => $user->user_id, 'milk_type' => $request->input('milk_type')])->select('fat', 'snf', 'rate')->get();
        $fats = $data->pluck('fat')->unique()->values();
        $snfs = $data->pluck('snf')->unique()->values();
        $rateData = [];

        foreach ($fats as $key1 => $fat) {
            $rates = [];
            foreach ($snfs as $key2 => $snf) {
                $rate = $this->getRateChart($data, $fat, $snf);
                $rates[] = $rate;
            }
            $rateData[] = $rates;
        }
        $newdata = [
            'fat' => $fats,
            'snf' => $snfs,
            'rate' => $rateData,
        ];
        return Helper::SuccessReturn($newdata, 'RATE_CHART_FETCHED');
    }
    public function rateChartUpload(Request $request)
    {
        $user = $request->user();
        $rules = [
            'rate_type' => ['required', 'in:' . implode(',', array_keys(RATE_CHART_TYPE))],
            'milk_type' => ['required', 'in:Cow,Buffalo,Mix,Other'],
            "rate_chart_file" => ['required', 'file', 'mimes:xls,xlsx'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        if ($request->hasFile('rate_chart_file')) {
            $file = $request->file('rate_chart_file');
            $path = 'public/ratechart/' . $request->rate_type . '/' . $user->user_id;
            if (!Storage::exists(storage_path($path))) {
                Storage::makeDirectory($path, 0777, true, true);
            }
            $filePath = $file->move(storage_path($path), $file->getClientOriginalName()); // Store the file on the server
            Excel::import(new MilkRateImport($request->milk_type, $request->rate_type, $user->user_id), $filePath);
        }
        return Helper::SuccessReturn(NULL, 'RATE_CHART_UPDATED');
    }
    public function sampleDownload()
    {
        $filePath = public_path('uploads/sample/sample-excel-chart.xlsx');
        $fileName = 'sample-excel-chart.xlsx';
        if (file_exists($filePath)) {
            return Response::download($filePath, $fileName);
        } else {
            return view('errors.404');
        }
    }
    public function download(Request $request)
    {
        $rules = [
            'rate_type' => ['required', 'in:' . implode(',', array_keys(RATE_CHART_TYPE))],
            'milk_type' => ['required', 'in:Cow,Buffalo,Mix,Other'],
            "user" => ['required', Rule::exists('users', 'user_id')],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        return  Excel::download(new MilkRateExport($request->milk_type, $request->rate_type, $request->user), 'milk_rate_chart.xlsx');
    }
    public function update(Request $request)
    {
        $user = $request->user();
        $rules = [
            'rate_type' => ['required', 'in:' . implode(',', array_keys(RATE_CHART_TYPE))],
            'milk_type' => ['required', 'in:Cow,Buffalo,Mix,Other'],
            "is_all" => ['nullable', 'boolean'],
            "value" => ['required', 'numeric'],
            "fat_from" => ['nullable', 'numeric', Rule::requiredIf(function () {
                return request('is_all') == false;
            })],
            "fat_to" => ['nullable', 'numeric', Rule::requiredIf(function () {
                return request('is_all') == false;
            })],
            "snf_from" => ['nullable', 'numeric', Rule::requiredIf(function () {
                return request('is_all') == false;
            })],
            "snf_to" => ['nullable', 'numeric', Rule::requiredIf(function () {
                return request('is_all') == false;
            })],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        if ($request->is_all) {
            MilkRateChart::where(['chart_type' => 'Purchase', 'user_id' => $user->user_id, 'milk_type' => $request->input('milk_type')])
                ->update([
                    'rate' => \DB::raw('rate + ' . $request->value)
                ]);
        } else {
            MilkRateChart::where([
                'chart_type' => 'Purchase',
                'user_id' => $user->user_id,
                'milk_type' => $request->input('milk_type')
            ])
                ->whereBetween('fat', [$request->input('fat_from'), $request->input('fat_to')])
                ->whereBetween('snf', [$request->input('snf_from'), $request->input('snf_to')])
                ->update([
                    'rate' => \DB::raw('rate + ' . $request->value)
                ]);
        }
        return Helper::SuccessReturn(null, 'RATE_UPDATED', ['type' => $request->rate_type]);
    }
}
