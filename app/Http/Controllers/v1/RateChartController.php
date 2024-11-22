<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Models\MilkRateChart;
use App\Imports\MilkRateImport;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Http\Middleware\SubDairyUserAccess;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RateChartController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(SubDairyUserAccess::class, only: ['index', 'sampleDownload', 'rateChart', 'upload']),
        ];
    }

    public function index()
    {
        $title = __('lang.Rate Charts');
        return view('user.rateCharts.index', compact('title'));
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
    public function rateChart($type, $name)
    {
        $type = array_search($type, RATE_CHART_TYPE);
        $name = array_search($name, RATE_MILK_TYPE);
        if (!empty($name) && !empty($type)) {
            $user = auth()->user();
            $data = MilkRateChart::where(['chart_type' => $type, 'milk_type' => $name, 'user_id' => $user->user_id])->get();
            if ($data->count() <= 0) {
                $demodata =  MilkRateChart::where(['chart_type' => $type, 'milk_type' => 'Cow', 'user_id' => 'DEMO'])->select(
                    'chart_type',
                    'milk_type',
                    'fat',
                    'snf',
                    'rate',
                    'user_id'
                )->get();
                $duplicatedData = $demodata->map(function ($record) use ($name, $user) {
                    $record->user_id = $user->user_id;
                    $record->milk_type = $name;
                    return $record;
                });
                MilkRateChart::insert($duplicatedData->toArray());
                $data = MilkRateChart::where(['chart_type' => $type, 'milk_type' => $name, 'user_id' => $user->user_id])->get();
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
            $ratechart = [
                'fat' => $fats,
                'snf' => $snfs,
                'rate' => $rateData,
            ];
            $title = __('lang.:name Milk :type Rate Chart', ['name' => __('lang.' . $name), 'type' => __('lang.' . $type)]);
            return view('user.rateCharts.view', compact('title', 'ratechart'));
        } else {
            return redirect()->route('user.rateCharts.list')->with('error', __('message.RATE_CHART_UNDEFINED_RATE_TYPE'));
        }
    }
    private function getRateChart($data, $fat, $snf)
    {
        foreach ($data as $innerArray) {
            if ($innerArray['fat'] === $fat && $innerArray['snf']  == $snf) {
                return $innerArray['rate'];
            }
        }
    }
    public function upload()
    {
        $title = __('lang.Rate Chart Uploads');
        return view('user.rateCharts.upload', compact('title'));
    }
    public function uploadChart(Request $request)
    {
        $user = auth()->user();
        //     return $request;
        // return implode(',', array_values(RATE_CHART_TYPE));
        $request->validate(
            [
                'rate_type' => ['required', 'in:' . implode(',', array_keys(RATE_CHART_TYPE))],
                'milk_type' => ['required', 'in:Cow,Buffalo,Mix,Other'],
                "rate_chart_file" => ['required', 'file', 'mimes:xls,xlsx'],
            ]
        );
        if ($request->hasFile('rate_chart_file')) {
            $file = $request->file('rate_chart_file');
            $path = 'public/' . $user->user_id . '/ratechart';
            if (!Storage::exists(storage_path($path))) {
                Storage::makeDirectory($path, 0777, true, true);
            }
            $filePath = $file->move(storage_path($path), $file->getClientOriginalName()); // Store the file on the server
            Excel::import(new MilkRateImport($request->milk_type, $request->rate_type, $user->user_id), $filePath);
        }
        return redirect()->route('user.rateCharts.list')->with('success', __('message.RATE_CHART_UPDATED'));
    }
}
