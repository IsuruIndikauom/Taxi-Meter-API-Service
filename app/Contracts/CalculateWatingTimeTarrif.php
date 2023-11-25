<?php

namespace App\Contracts;

interface CalculateWatingTimeTarrif {
    function totalWaitingTimeTarrif ( $total_time, $distance, $rate_per_minute, $current_tarrif );
}

