<?php

namespace App\Services;
use App\Contracts\CalculateWatingTimeTarrif;
use Carbon\Carbon;

class ManualWaitingTimeTarrifCalculator implements CalculateWatingTimeTarrif {
    public  function totalWaitingTimeTarrif (
        $total_time,
        $distance,
        $rate_per_minute,
        $current_tarrif ) {
            if ( $distance <= 0.5 ) {
                return number_format( ( $rate_per_minute * $total_time / 60 ), 2 );
            } else {
                return $current_tarrif;
            }
        }
    }