<?php

namespace App\Contracts;

interface CalculateDistance {
    function distanceInMetres( $start_log, $start_lat, $end_log, $end_lat );
}
