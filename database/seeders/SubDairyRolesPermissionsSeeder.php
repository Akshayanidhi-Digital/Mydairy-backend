<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DealerRolePermissions;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubDairyRolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $routes = Route::getRoutes();
        $userRoutes = [];
        $ignorePrefixes = ['dashboard', 'childUser', 'profile', 'lang', 'settings', 'masters',];
        $ignoreSuffixes = ['info', 'calculate', 'print', 'print.all', 'store'];
        foreach ($routes as $route) {
            $routeName = $route->getName();
            if ($routeName && strpos($routeName, 'user.') === 0) {
                $formattedRouteName = substr($routeName, strlen('user.'));
                $shouldIgnore = false;

                foreach ($ignorePrefixes as $prefix) {
                    if (strpos($formattedRouteName, $prefix) === 0) {
                        $shouldIgnore = true;
                        break;
                    }
                }
                if (!$shouldIgnore) {
                    foreach ($ignoreSuffixes as $suffix) {
                        if (substr($formattedRouteName, -strlen($suffix)) === $suffix) {
                            $shouldIgnore = true;
                            break;
                        }
                    }
                }
                if (!$shouldIgnore) {
                    DealerRolePermissions::updateOrCreate(['name' => $formattedRouteName]);
                }
            }
        }
    }
}
