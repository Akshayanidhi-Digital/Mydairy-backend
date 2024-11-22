<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\PackageSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // PackageSeeder::class,
            ProductsUnitTypesSeeder::class,
            SubDairyRolesSeeder::class,
            SubDairyRolesPermissionsSeeder::class,
        ]);
    }
}
