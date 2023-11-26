<?php

namespace App\Contracts;

interface CalculateWaiting {
    function totalWaitingTimeTarrif ( $total_time, $rate_per_minute );

    function totalWaitingTime( $current_wating_time, $total_waiting_time, $distance );
}

