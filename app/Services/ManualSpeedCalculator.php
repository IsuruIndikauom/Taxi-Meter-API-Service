<?php

namespace App\Services;
use App\Contracts\CalculateSpeed;

class ManualSpeedCalculator implements CalculateSpeed {
    public  function speedInKMPH( $time_difference, $distance ) {
        if ( checkNonZeroValues( [ $distance, $time_difference ] ) ) {
            return sprintf( '%.2f', round( ( ( $distance / 1000 ) / ( $time_difference / 3600 ) ), 2 ) );
        } else {
            return sprintf( '%.2f', round( 0, 2 ) );
        }
    }
}