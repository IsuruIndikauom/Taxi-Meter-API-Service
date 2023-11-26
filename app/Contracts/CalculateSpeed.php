<?php

namespace App\Contracts;

interface CalculateSpeed {
    function speedInKMPH( $time_difference, $distance );
}
