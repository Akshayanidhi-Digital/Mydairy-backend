<?php

namespace Database\Seeders;

use App\Models\ProductsUnitTypes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsUnitTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'name' => "SQUARE METERS",
                'unit'=> "SQM"
            ],
            [
                'name' => "SQUARE YARDS",
                'unit'=> "SQY"
            ],
            [
                'name' => "TABLETS",
                'unit'=> "TBS"
            ],
            [
                'name' => "TEN GROSS",
                'unit'=> "TGM"
            ],
            [
                'name' => "TONNES",
                'unit'=> "TON"
            ],
            [
                'name' => "THOUSANDS",
                'unit'=> "THD"
            ],
            [
                'name' => "TUBES",
                'unit'=> "TUB"
            ],
            [
                'name' => "US GALLONS",
                'unit'=> "UGS"
            ],
            [
                'name' => "UNITS",
                'unit'=> "UNT"
            ],
            [
                'name' => "YARDS",
                'unit'=> "YDS"
            ],
            [
                'name' => "MILLI LITER",
                'unit'=> "MLT"
            ],
            [
                'name' => "METERS",
                'unit'=> "MTR"
            ],
            [
                'name' => "NUMBERS",
                'unit'=> "NOS"
            ],
            [
                'name' => "PACKS",
                'unit'=> "PAC"
            ],
            [
                'name' => "PIECES",
                'unit'=> "PCS"
            ],
            [
                'name' => "PAIRS",
                'unit'=> "PRS"
            ],
            [
                'name' => "QUINTAL",
                'unit'=> "QTL"
            ],
            [
                'name' => "ROLLS",
                'unit'=> "ROL"
            ],
            [
                'name' => "SETS",
                'unit'=> "SET"
            ],
            [
                'name' => "SQUARE FEET",
                'unit'=> "SQF"
            ],
            [
                'name' => "SQUARE METERS",
                'unit'=> "SQM"
            ],
            [
                'name' => "CUBIC",
                'unit'=> "METER CCM"
            ],
            [
                'name' => "CETNI METER",
                'unit'=> "CMS"
            ],
            [
                'name' => "CARTONS",
                'unit'=> "CTN"
            ],
            [
                'name' => "DOZEN",
                'unit'=> "DOZ"
            ],
            [
                'name' => "DRUN",
                'unit'=> "DRM"
            ],
            [
                'name' => "GREAT GROSS",
                'unit'=> "GGR"
            ],
            [
                'name' => "GROSS",
                'unit'=> "GRS"
            ],
            [
                'name' => "GRAM",
                'unit'=> "GMS"
            ],
            [
                'name' => "GROSS YARDS",
                'unit'=> "GYD"
            ],
            [
                'name' => "KILO GRAMS",
                'unit'=> "KGS"
            ],
            [
                'name' => "KILOLITER",
                'unit'=> "KLR"
            ],
            [
                'name' => "BAGS",
                'unit'=> "BAG"
            ],
            [
                'name' => "BALE",
                'unit'=> "BAL"
            ],
            [
                'name' => "BUNDLES",
                'unit'=> "BDL"
            ],
            [
                'name' => "BILLIONS OF UNITS",
                'unit'=> "BOU"
            ],
            [
                'name' => "BOX",
                'unit'=> "BOX"
            ],
            [
                'name' => "BOTTLES",
                'unit'=> "BTL"
            ],
            [
                'name' => "BUNCHES",
                'unit'=> "BUN"
            ],
            [
                'name' => "CANS",
                'unit'=> "CAN"
            ],
            [
                'name' => "CUBIC",
                'unit'=> "CBM"
            ],
            [
                'name' => "OTHERS",
                'unit'=> "OTH"
            ],
        ];
        foreach ($datas as  $value) {
           ProductsUnitTypes::updateOrCreate($value,$value);
        }
    }
}
