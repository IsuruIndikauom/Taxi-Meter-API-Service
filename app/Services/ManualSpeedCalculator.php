<?php

namespace App\Services;
use App\Contracts\CalculateSpeed;

class ManualSpeedCalculator implements CalculateSpeed {
    public  function speedInKMPH( $time_difference, $distance ) {
        if ( checkNonZeroValues( [ $distance, $time_difference ] ) ) {
            $distanceKilometers = $distance / 1000;
            $timeHours = $time_difference / 3600;
            return number_format( ( $distanceKilometers / $timeHours ), 2 );
        } else {
            return number_format( 0, 2 );
        }
    }
}