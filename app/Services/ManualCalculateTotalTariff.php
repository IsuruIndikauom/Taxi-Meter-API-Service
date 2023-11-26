<?php

namespace App\Services;
use App\Contracts\CalculateTotalTariff;

class ManualCalculateTotalTariff implements CalculateTotalTariff {
    public  function total( $fix_rate_tariff, $distance_tariff, $time_tariff ) {
        return number_format( $fix_rate_tariff+$distance_tariff+$time_tariff, 2 );
        ;
    }
}