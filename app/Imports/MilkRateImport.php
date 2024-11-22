<?php

namespace App\Imports;

use App\Models\MilkRateChart;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Illuminate\Validation\Rule;


class MilkRateImport implements ToCollection
{
    public $milkType;
    public $chartType;
    public $user_id;
    public function __construct($milkType,$chartType,$user_id)
    {
        $this->milkType = $milkType;
        $this->chartType = $chartType;
        $this->user_id = $user_id;
    }
    public function collection(Collection $rows)
    {

        Log::info('counts ...' . $rows->count());
        $floatArray = [];
        foreach ($rows as $row) {
            $hasFloat = false;
            foreach ($row as $value) {
                if (is_float($value)) {
                    $hasFloat = true;
                    break;
                }
            }
            if ($hasFloat) {
                $floatArray[] = $row;
            }
        }
        $snf = [];
        $fat = [];
        $rate = [];
        $rowCount = count($floatArray);
        $columnCount = ($rowCount > 0) ? count($floatArray[0]) : 0;
        for ($i = 0; $i < $rowCount; $i++) {
            $ratesub = [];
            for ($j = 0; $j < $columnCount; $j++) {
                if ($i == 0) {
                    if ($j != 0) {
                        $snf[] = $floatArray[0][$j];
                    }
                }
                if ($i != 0 && $j != 0) {
                    $ratesub[] = $floatArray[$i][$j];
                }

                if ($j == 0) {
                    if ($i != 0) {
                        $fat[] = $floatArray[$i][0];
                    }
                }
            }
            if (count($ratesub) > 0) {
                $rate[] = $ratesub;
            }
        }

        foreach($rate as $key1=> $rt){
            foreach($rt as $key2=> $r){
                $data = [
                    'user_id'=>$this->user_id,
                    'chart_type'=>$this->chartType,
                    'milk_type'=>$this->milkType,
                    'fat'=>$fat[$key1],
                    'snf'=>$snf[$key2],
                    'rate'=>$r
                ];
                MilkRateChart::updateOrCreate($data,$data);
            }
        }
    }
}
