<?php

namespace App\Services;
use App\Contracts\CalculateSpeed;

class ManualSpeedCalculator implements CalculateSpeed {
    public  function speed( $distace, $time ) {
        return 0;
    }
}