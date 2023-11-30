<?php

namespace App\Services;
use App\Contracts\CalculateWaiting;
use Carbon\Carbon;

class ManualWaitingCalculator implements CalculateWaiting {
    public  function totalWaitingTimeTariff ( $total_time, $rate_per_minute ) {
        if ( checkNonZeroValues( [ $total_time, $rate_per_minute ] ) ) {
            return sprintf( '%.2f', round( ( $rate_per_minute * $total_time / 60 ), 2 ) );
        } else {
            return sprintf( '%.2f', round( 0, 2 ) );
        }
    }

    public  function totalWaitingTime ( $current_waiting_time, $total_waiting_time, $distance ):int {
        if ( $distance <= 0.5 ) {
            return $current_waiting_time+ $total_waiting_time;
        } else {
            return $total_waiting_time;
        }
    }
}