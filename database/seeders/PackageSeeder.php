<?php

namespace Database\Seeders;

use App\Models\Pakeage;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'name' => 'Demo',
                'category' => 'single',
                'user_count' => 0,
                'farmer_count' => 20,
                'price' => 0,
                'duration' => 15,
                "duration_type" => "day",
                'description' => 'plan for demo',
            ],
            [
                'name' => 'Basic',
                'category' => 'single',
                'user_count' => 0,
                'farmer_count' => 50,
                'price' => 199.99,
                'duration' => 28,
                "duration_type" => "day",
                'description' => 'basic plan for single user type',
            ],
            [
                'name' => 'Silver',
                'category' => 'single',
                'user_count' => 0,
                'farmer_count' => 100,
                'price' => 349.99,
                'duration' => 26,
                "duration_type" => "day",
                'description' => 'Silver plan for single user type',
            ],
            [
                'name' => 'Basic',
                'category' => 'multiple',
                'user_count' => 5,
                'farmer_count' => 50,
                'price' => 999.99,
                'duration' => 28,
                "duration_type" => "day",
                'description' => 'Silver plan for single user type',
            ]
        ];
        foreach ($datas as $data) {
            Pakeage::updateOrCreate($data, $data);
        }
    }
}
