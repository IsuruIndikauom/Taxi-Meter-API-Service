<?php

namespace App\Contracts;

interface CalculateDistanceTariff {
    function distanceTariff ( $total_distance, $tariff_per_km );
}
