<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceAccountPath = storage_path('countries+states+cities.json');
        if (file_exists($serviceAccountPath)) {
            $fileContents = file_get_contents($serviceAccountPath);
            $countries =  collect(json_decode($fileContents));
            $cn =  $countries->where('id', 101)->first(); // for india only.
            $in  = [
                'name' => $cn->name,
                'iso2' => $cn->iso2,
                'region' => $cn->region,
                'phone_code' => $cn->phone_code,
                'currency' => $cn->currency,
                'currency_name' => $cn->currency_name,
                'currency_symbol' => $cn->currency_symbol,
                'timezones' => json_encode($cn->timezones),
            ];
            $cID = DB::table('countries')->insertGetId($in);
            $states = $cn->states;
            foreach ($states as $st) {
                $city = $st->cities;
                $stID =  DB::table('states')->insertGetId([
                    'country_id' => $cID,
                    'name' => $st->name,
                    'state_code' => $st->state_code,
                    'type' => $st->type,
                ]);
                foreach ($city as $ct) {
                    DB::table('cities')->insert([
                        'name' => $ct->name,
                        'state_id' => $stID,
                    ]);
                }
            }
        }
    }
}
