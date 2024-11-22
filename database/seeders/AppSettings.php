<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppSettings extends Seeder
{

    public function run(): void
    {
        $datas = [
            [
                'name' => 'app_name',
                'value' => 'MyDairy',
            ],
            [
                'name' => 'GOOGLE_CLIENT_ID',
                'value' => '',
            ],
            [
                'name' => 'GOOGLE_CLIENT_SECRET',
                'value' => '',
            ],
            [
                'name' => 'GOOGLE_REDIRECT_URI',
                'value' => '',
            ],
            [
                'name' => 'FACEBOOK_CLIENT_ID',
                'value' => '',
            ],
            [
                'name' => 'FACEBOOK_CLIENT_SECRET',
                'value' => '',
            ],
            [
                'name' => 'FACEBOOK_REDIRECT_URI',
                'value' => '',
            ],
        ];
    }
}
