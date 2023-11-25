<?php

namespace App\Services;
use App\Contracts\CalculateDistanceTarrif;

class ManualCalculateDistanceTarrif implements CalculateDistanceTarrif {
    public  function distanceTarrif ( $total_distance, $tarrif_per_km ) {
        return 0;
    }
}