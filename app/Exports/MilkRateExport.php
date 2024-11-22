<?php

namespace App\Exports;

use App\Models\MilkRateChart;
use Maatwebsite\Excel\Concerns\FromArray;

class MilkRateExport implements FromArray
{
    protected $milkType;
    protected $chartType;
    protected $user;

    public function __construct($milkType, $chartType, $user)
    {
        $this->milkType = $milkType;
        $this->chartType = $chartType;
        $this->user = $user;
    }

    public function array(): array
    {
        $data = MilkRateChart::where('user_id', $this->user)
            ->where('milk_type', $this->milkType)
            ->where('chart_type', $this->chartType)
            ->select('fat', 'snf', 'rate')
            ->get();
        $fat = $data->pluck('fat')->unique()->values()->toArray();
        $snf = $data->pluck('snf')->unique()->values()->toArray();
        foreach ($fat as $key1 => $f) {
            $rates = [];
            foreach ($snf as $key2 => $s) {
                $rate = $this->getRateChart($data, $f, $s);
                $rates[] = $rate;
            }
            $rates[] = $rates;
        }
        $exportData = [];
        $exportData[] = array_merge([''], $snf);
        foreach ($fat as $key => $f) {
            $row = [$f];
            foreach ($snf as $s) {
                $row[] = $this->getRateChart($data, $f, $s);
            }
            $exportData[] = $row;
        }
        return $exportData;
    }
    private function getRateChart($data, $fat, $snf)
    {
        foreach ($data as $innerArray) {
            if ($innerArray['fat'] === $fat && $innerArray['snf']  == $snf) {
                return $innerArray['rate'];
            }
        }
    }
}
