<?php

namespace App\Services;
use App\Contracts\CalculateWatingTime;

class ManualTimeCalculator implements CalculateWatingTime {
    public  function time( $last_update_time ) {
        return 0;
    }
}