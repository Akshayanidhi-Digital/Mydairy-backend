<?php

namespace App\Services;

class DistanceService
{
    public function haversine($lat1, $lng1, $lat2, $lng2)
    {
        if (($lat1 == $lat2) && ($lng1 == $lng2)) {
            return 0;
        }
        $theta = $lng1 - $lng2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return ($miles * 1.609344);

        // $lat1 = deg2rad($lat1);
        // $lng1 = deg2rad($lng1);
        // $lat2 = deg2rad($lat2);
        // $lng2 = deg2rad($lng2);
        // $dlon = $lng2 - $lng1;
        // $dlat = $lat2 - $lat1;
        // $a = sin($dlat / 2) ** 2 + cos($lat1) * cos($lat2) * sin($dlon / 2) ** 2;
        // $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        // $radius = 6371;
        // return $radius * $c;
    }
}
