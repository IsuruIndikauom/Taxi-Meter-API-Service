<?php

namespace App\Services;
use App\Contracts\CalculateDistance;

class ManualDistanceCalculator implements CalculateDistance {
    public  function distace( $start_log, $start_lat, $end_log, $end_lat ) {
        return 0;
    }
}