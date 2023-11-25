<?php

namespace App\Http\Controllers;

use App\Http\Requests\TripStartRequest;
use App\Http\Requests\TripInProgressRequest;
use App\Models\Trip;
Use Auth;
use App\Contracts\CalculateDistance;
use App\Contracts\CalculateWatingTimeTarrif;
use App\Contracts\CalculateSpeed;
use App\Contracts\CalculateDistanceTarrif;
use App\Contracts\CalculateTotalTarrif;

class TripController extends Controller {
    public function start( TripStartRequest $request, Trip $trip ) {
        $user_id = Auth::User()->id;
        if ( $trip->tripExist( $user_id ) ) {
            return $this->badrequest( 'Trip exists for this user' );
        } else {
            return $this->success( 'Trip Started', $trip->startTrip( $request, $user_id ) );
        }
    }

    public function inProgress(
        TripInProgressRequest $request,
        Trip $trip,
        CalculateDistance $distance,
        CalculateWatingTimeTarrif $total_waiting_time_tarrif,
        CalculateSpeed $speed,
        CalculateDistanceTarrif $distance_tarrif,
        CalculateTotalTarrif $total_tarrif ) {
            if ( $trip->exists() ) {
                $distance = $distance->distanceInMetres( $trip->start_latitude, $trip->start_longitude,  $request->current_latitude, $request->current_longitude );
                $total_waiting_time_tarrif = $total_waiting_time_tarrif->totalWaitingTimeTarrif ( $trip->timeDiffInSeconds()+ $trip->total_waiting_time, $distance, $trip->rate_per_minute, $trip->waiting_tarrif );
                $speed = $speed->speedInKMPH( $trip->timeDiffInSeconds(), $distance );
                $distance_tarrif = $distance_tarrif->distanceTarrif ( $distance + $trip->ride_distance, $trip->rate_per_km );
                $total_tarrif = $total_tarrif->total( $trip->fix_rate, $distance_tarrif, $total_waiting_time_tarrif );
                return $this->success( 'Trip Started', $trip->tripInprogress( $request ) );
            } else {
                return $this->badrequest( 'Trip does not exists' );
            }
        }

    }
