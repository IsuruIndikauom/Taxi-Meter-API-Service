<?php

namespace App\Services;
use App\Contracts\CalculateDistanceTarrif;

class ManualCalculateDistanceTarrif implements CalculateDistanceTarrif {
    public  function distanceTarrif ( $total_distance, $tarrif_per_km ) {
        $distanceKM = $total_distance / 1000;
        return number_format( ( $distanceKM * $tarrif_per_km ), 2 );

    }
}