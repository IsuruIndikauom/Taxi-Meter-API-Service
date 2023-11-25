<?php

namespace App\Contracts;

interface CalculateWatingTime {
    function time( $last_update_time );
}
