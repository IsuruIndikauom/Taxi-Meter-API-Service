<?php

namespace App\Services;
use App\Contracts\CalculateDistance;

class ManualDistanceCalculator implements CalculateDistance {
    //Haversine formula
    public  function distanceInMetres( $start_lat, $start_lon, $end_lat, $end_lon ) {
        $earthRadius = 6371000;
        // Earth's radius in meters

        $dLat = deg2rad( $end_lat - $start_lat );
        $dLon = deg2rad( $end_lon - $start_lon );

        $a = sin( $dLat / 2 ) * sin( $dLat / 2 ) +
        cos( deg2rad( $start_lat ) ) * cos( deg2rad( $end_lat ) ) *
        sin( $dLon / 2 ) * sin( $dLon / 2 );

        $c = 2 * atan2( sqrt( $a ), sqrt( 1 - $a ) );

        return number_format( ( $earthRadius * $c), 2 );
    }
    //Vincenty formula
    function calculateDistanceVincenty($lat1, $lon1, $lat2, $lon2) {
        $a = 6378137; // Earth's semi-major axis in meters
        $b = 6356752.314245;
        // Earth's semi-minor axis in meters
        $f = 1 / 298.257223563; // Earth's flattening
        $L = deg2rad( $lon2 - $lon1 );

        $U1 = atan( ( 1 - $f ) * tan( deg2rad( $lat1 ) ) );
        $U2 = atan( ( 1 - $f ) * tan( deg2rad( $lat2 ) ) );
        $sinU1 = sin( $U1 );
        $cosU1 = cos( $U1 );
        $sinU2 = sin( $U2 );
        $cosU2 = cos( $U2 );

        $lambda = $L;
        $lambdaP = 2 * M_PI;

        $iterLimit = 100;
        $cosSqAlpha = null;
        $sinSigma = null;
        $cos2SigmaM = null;
        $cosSigma = null;
        $sigma = null;
        $sinLambda = null;
        $cosLambda = null;

        while ( abs( $lambda - $lambdaP ) > 1e-12 && --$iterLimit > 0 ) {
            $sinLambda = sin( $lambda );
            $cosLambda = cos( $lambda );
            $sinSigma = sqrt( ( $cosU2 * $sinLambda ) * ( $cosU2 * $sinLambda ) +
            ( $cosU1 * $sinU2 - $sinU1 * $cosU2 * $cosLambda ) *
            ( $cosU1 * $sinU2 - $sinU1 * $cosU2 * $cosLambda ) );
            if ( $sinSigma == 0 ) {
                return 0;
                // Co-incident points
            }
            $cosSigma = $sinU1 * $sinU2 + $cosU1 * $cosU2 * $cosLambda;
            $sigma = atan2( $sinSigma, $cosSigma );
            $sinAlpha = $cosU1 * $cosU2 * $sinLambda / $sinSigma;
            $cosSqAlpha = 1 - $sinAlpha * $sinAlpha;
            $cos2SigmaM = $cosSigma - 2 * $sinU1 * $sinU2 / $cosSqAlpha;

            $C = $f / 16 * $cosSqAlpha * ( 4 + $f * ( 4 - 3 * $cosSqAlpha ) );
            $lambdaP = $lambda;
            $lambda = $L + ( 1 - $C ) * $f * $sinAlpha *
            ( $sigma + $C * $sinSigma *
            ( $cos2SigmaM + $C * $cosSigma *
            ( -1 + 2 * $cos2SigmaM * $cos2SigmaM ) ) );
        }

        if ( $iterLimit == 0 ) {
            return NaN;
            // Formula failed to converge
        }

        $uSq = $cosSqAlpha * ( $a * $a - $b * $b ) / ( $b * $b );
        $A = 1 + $uSq / 16384 * ( 4096 + $uSq * ( -768 + $uSq * ( 320 - 175 * $uSq ) ) );
        $B = $uSq / 1024 * ( 256 + $uSq * ( -128 + $uSq * ( 74 - 47 * $uSq ) ) );
        $deltaSigma = $B * $sinSigma *
        ( $cos2SigmaM + $B / 4 *
        ( $cosSigma * ( -1 + 2 * $cos2SigmaM * $cos2SigmaM ) -
        $B / 6 * $cos2SigmaM * ( -3 + 4 * $sinSigma * $sinSigma ) *
        ( -3 + 4 * $cos2SigmaM * $cos2SigmaM ) ) );
        $distance = $b * $A * ( $sigma - $deltaSigma );

        return number_format( $distance, 2 );
    }

}