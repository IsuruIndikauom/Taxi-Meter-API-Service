<?php

namespace App\Contracts;

interface CalculateDistanceTarrif {
    function distanceTarrif ( $total_distance, $tarrif_per_km );
}
