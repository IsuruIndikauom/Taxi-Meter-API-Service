<?php

namespace App\Services;
use App\Contracts\CalculateWaiting;
use Carbon\Carbon;

class ManualWaitingCalculator implements CalculateWaiting {
    public  function totalWaitingTimeTariff ( $total_time, $rate_per_minute ) {
        if ( checkNonZeroValues( [ $total_time, $rate_per_minute ] ) ) {
            return number_format( ( $rate_per_minute * $total_time / 60 ), 2 );
        } else {
            return number_format( 0, 2 );
        }

    }

    public  function totalWaitingTime ( $current_waiting_time, $total_waiting_time, $distance ) {
        if ( $distance <= 0.5 ) {
            return number_format( ( $current_waiting_time + $total_waiting_time ), 2 );
        } else {
            return number_format( $total_waiting_time, 2 );
        }
    }
}