<?php

namespace App\Services;
use App\Contracts\CalculateDistanceTariff;

class ManualCalculateDistanceTariff implements CalculateDistanceTariff {
    public  function distanceTariff ( $total_distance, $tariff_per_km ) {
        if ( checkNonZeroValues( [ $total_distance ] ) ) {
            $distanceKM = $total_distance / 1000;
            return number_format( ( $distanceKM * $tariff_per_km ), 2 );
        } else {
            return number_format( 0, 2 );
        }
    }
}