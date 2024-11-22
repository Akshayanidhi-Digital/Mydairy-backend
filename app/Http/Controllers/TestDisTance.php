<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\DistanceService;
use Illuminate\Support\Facades\DB;

class TestDisTance extends Controller
{
    protected $distanceService;

    public function __construct()
    {
        $this->distanceService = new DistanceService();
    }

    public function calculateRouteDistance()
    {
        $coordinates = [
            'p' => ['lat' => 26.844225975098983, 'lng' => 75.80279424779599],
            's' => ['lat' => 26.89430124149142, 'lng' => 75.80331142724721],
            'h' => ['lat' => 26.929038236212637, 'lng' => 75.82554785396358],
        ];

        $dairyIds = ['p',  's', 'h'];
        $totalDistance = 0;
        $previousCoordinates = null;

        foreach ($dairyIds as $dairyId) {
            if (isset($coordinates[$dairyId])) {
                $currentCoordinates = $coordinates[$dairyId];

                if ($previousCoordinates) {
                    $distance = $this->distanceService->haversine(
                        $previousCoordinates['lat'],
                        $previousCoordinates['lng'],
                        $currentCoordinates['lat'],
                        $currentCoordinates['lng']
                    );
                    $totalDistance += $distance;
                }

                $previousCoordinates = $currentCoordinates;
            }
        }

        return response()->json(['total_distance' => number_format($totalDistance, 2) . ' km']);
    }
}
