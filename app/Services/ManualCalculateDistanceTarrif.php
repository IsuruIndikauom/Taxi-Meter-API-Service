<?php

namespace App\Services;
use App\Contracts\CalculateDistanceTarrif;

class ManualCalculateDistanceTarrif implements CalculateDistanceTarrif {
    public  function distanceTarrif ( $total_distance, $tarrif_per_km ) {
        if ( checkNonZeroValues( [ $total_distance ] ) ) {
            $distanceKM = $total_distance / 1000;
            return number_format( ( $distanceKM * $tarrif_per_km ), 2 );
        } else {
            return number_format( 0, 2 );
        }
    }
}