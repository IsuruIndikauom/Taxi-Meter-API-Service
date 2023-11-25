<?php

namespace App\Services;
use App\Contracts\CalculateSpeed;

class ManualSpeedCalculator implements CalculateSpeed {
    public  function speedInKMPH( $time_difference, $distance ) {
        $distanceKilometers = $distance / 1000;
        $timeHours = $time_difference / 3600;
        return number_format( ( $distanceKilometers / $timeHours ), 2 );
    }
}