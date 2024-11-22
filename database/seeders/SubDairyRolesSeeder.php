<?php

namespace Database\Seeders;

use App\Models\DealerRoles;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubDairyRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'short_name' => "BMC",
                'name' => "Bulk Milk Cooler",
            ],
            [
                'short_name' => "DCS",
                'name' => "Dairy Cooperative Society",
            ],
            [
                'short_name' => "PDCS",
                'name' => "Proposed Dairy Cooperative Society",
            ],
            [
                'short_name' => "MCC",
                'name' => "Milk Cooler Center",
            ],
            // [
            //     'short_name' => "DCS",
            //     'name' => "Dairy Cooperative Society",
            // ],
        ];
        foreach ($datas as $data) {
            DealerRoles::UpdateOrcreate($data);
        }
    }
}
