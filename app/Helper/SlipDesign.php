<?php

namespace App\Helper;

use Mike42\Escpos\Printer;

class SlipDesign
{
    public function milkBuySlip($printer, $record, $user)
    {
        $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("MYDAIRY\n");
        $printer->selectPrintMode();
        $printer->text("Milk Slip\n");
        $printer->feed();
        // Print seller info
        $printer->setEmphasis(true);
        $printer->text("{$record->seller->name} s/o {$record->seller->father_name}\n");
        $printer->text("Mobile No.: {$record->seller->country_code} {$record->seller->mobile}\n");
        $printer->setEmphasis(false);
        $printer->feed();
        // Print details
        $details = [
            "Date" => $record->date,
            "Shift" => $record->shift,
            "Milk Type" => array_search($record->milk_type, MILK_TYPE),
            "Weight" => number_format($record->quantity, 2) . " Ltr",
            "FAT" => $record->fat != 0 ? number_format($record->fat, 2) : 'NA',
            "SNF" => $record->snf != 0 ? number_format($record->snf, 2) : 'NA',
            "CLR" => $record->clr != 0 ? number_format($record->clr, 2) : 'NA',
            "Rate/ltr" => "₹" . number_format($record->price, 2),
            "Total" => "₹" . number_format($record->total_price, 2)
        ];
        foreach ($details as $key => $value) {
            $printer->text(str_pad($key, 20, " ") . ": " . str_pad($value, 20, " ", STR_PAD_LEFT) . "\n");
        }
        $printer->feed();
        // Print footer
        $printer->setEmphasis(true);
        $printer->text($user->name . "\n");
        $printer->text("Mobile No.: {$user->country_code} {$user->mobile}\n");
        $printer->setEmphasis(false);

        // Cut the receipt and open the cash drawer
        $printer->cut();
        $printer->pulse();
    }
}
