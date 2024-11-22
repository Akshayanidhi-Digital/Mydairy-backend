<?php

namespace App\Http\Controllers\Api\v1;

use App\Helper\Helper;
use Illuminate\Http\Request;
use App\Models\MilkBuyRecords;
use App\Models\MilkSaleRecords;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MilkRecordsController extends Controller
{
    public function records(Request $request)
    {
        $user = $request->user();
        $rules = [
            'record_type' => ['required', 'in:sell,buy'],
            'record_date' => ['required', 'date'],
            "record_shift" => ['nullable', 'in:M,E,D'],
            'milk_type' => ['nullable', 'in:Cow,Buffalo,Mix,Other'] //0=Cow,1=Buffalo,2=Mix,3=Other
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $date = $request->input('record_date');
        $recordType = $request->input('record_type');
        $milkType = $request->input('milk_type');
        $recordShift = $request->input('record_shift');
        $data = $this->getRecords($recordType, $user->user_id, $date, $milkType, $recordShift);
        return Helper::SuccessReturn($data, 'RECORDS_FETCHED_SUCCESSFULLY');
    }
    private function getRecords($type, $user_id, $date, $milkType, $recordShift)
    {
        if ($type == 'sell') {
            $records = MilkSaleRecords::where([
                'seller_id' => $user_id,
                "trash" => 0,
            ])
                ->when($milkType && array_key_exists($milkType, MILK_TYPE), function ($query) use ($milkType) {
                    $query->where('milk_type', MILK_TYPE[$milkType]);
                })
                ->when($recordShift, function ($query) use ($recordShift) {
                    $query->where('shift', $recordShift);
                })
                ->whereDate('date', $date)
                ->WithCostumer()
                ->get();
        } else {
            $records = MilkBuyRecords::where([
                'buyer_id' => $user_id,
                'trash' => false,
                "is_deleted" => false
            ])
                ->when($milkType && array_key_exists($milkType, MILK_TYPE), function ($query) use ($milkType) {
                    $query->where('milk_type', MILK_TYPE[$milkType]);
                })
                ->when($recordShift, function ($query) use ($recordShift) {
                    $query->where('shift', $recordShift);
                })
                ->whereDate('date', $date)
                ->WithCostumer()
                ->get();
        }
        return $records;
    }
    public function recordsPrint(Request $request)
    {
        $user = $request->user();
        $rules = [
            'record_type' => ['required', 'in:sell,buy'],
            'record_date' => ['required', 'date'],
            "record_shift" => ['nullable', 'in:M,E,D'],
            'milk_type' => ['nullable', 'in:Cow,Buffalo,Mix,Other'] //0=Cow,1=Buffalo,2=Mix,3=Other
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::FalseReturn(null, $validator->errors()->first());
        }
        $date = $request->input('record_date');
        if ($request->input('record_type') == 'buy') {
            $view_name = 'pdf.milk.milk_buy_records';
        } else {
            $view_name = 'pdf.milk.milk_sell_records';
        }
        $date = $request->input('record_date');
        $recordType = $request->input('record_type');
        $milkType = $request->input('milk_type');
        $recordShift = $request->input('record_shift');
        $datas = $this->getRecords($recordType, $user->user_id, $date, $milkType, $recordShift);
        $shift = ($request->record_shift) ? $request->record_shift : 'All';
        $pdf = Pdf::loadView($view_name, compact('datas', 'user', 'date', 'shift'))
            ->setPaper('A4');
        $name = 'milk_' . $request->input('record_type') . '_records' . $date;
        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $name . '"');
    }
}
