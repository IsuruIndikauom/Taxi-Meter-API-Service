<?php

namespace App\Contracts;

interface CalculateTotalTarrif {
    function total( $fix_rate_tarrif, $distance_tarrif, $time_tarrif );
}
