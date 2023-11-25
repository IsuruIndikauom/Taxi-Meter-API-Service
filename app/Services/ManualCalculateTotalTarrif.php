<?php

namespace App\Services;
use App\Contracts\CalculateTotalTarrif;

class ManualCalculateTotalTarrif implements CalculateTotalTarrif {
    public  function total( $fix_rate_tarrif, $distance_tarrif, $time_tarrif ) {
        return 0;
    }
}