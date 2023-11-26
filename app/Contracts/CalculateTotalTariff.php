<?php

namespace App\Contracts;

interface CalculateTotalTariff {
    function total( $fix_rate_tariff, $distance_tariff, $time_tariff );
}
