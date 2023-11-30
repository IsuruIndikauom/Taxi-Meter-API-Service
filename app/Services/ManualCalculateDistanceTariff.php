<?php

namespace App\Services;
use App\Contracts\CalculateDistanceTariff;

class ManualCalculateDistanceTariff implements CalculateDistanceTariff {
    public  function distanceTariff ( $total_distance, $tariff_per_km ) {
        if ( checkNonZeroValues( [ $total_distance ] ) ) {
            return sprintf( '%.2f', round( ( ( $total_distance / 1000 ) * $tariff_per_km ), 2 ) );
        } else {
            return sprintf( '%.2f', round( 0, 2 ) );
        }
    }
}