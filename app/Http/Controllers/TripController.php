<?php

namespace App\Http\Controllers;

use App\Http\Requests\TripStartRequest;
use App\Http\Requests\TripInProgressRequest;
use App\Models\Trip;
Use Auth;
use Carbon\Carbon;
use App\Contracts\CalculateDistance;
use App\Contracts\CalculateWaiting;
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
        CalculateWaiting $total_waiting,
        CalculateSpeed $speed,
        CalculateDistanceTarrif $distance_tarrif,
        CalculateTotalTarrif $total_tarrif ) {
            if ( $trip->exists() ) {
                $distance = $distance->distanceInMetres( $trip->start_latitude, $trip->start_longitude,  $request->current_latitude, $request->current_longitude );
                $total_waiting_time = $total_waiting->totalWaitingTime ( $trip->timeDiffInSeconds(), $trip->total_waiting_time, $distance );
                $total_waiting_time_tarrif = $total_waiting->totalWaitingTimeTarrif ( $total_waiting_time, $trip->rate_per_minute );
                $speed = $speed->speedInKMPH( $trip->timeDiffInSeconds(), $distance );
                $distance_tarrif = $distance_tarrif->distanceTarrif ( $distance + $trip->ride_distance, $trip->rate_per_km );
                $total_tarrif = $total_tarrif->total( $trip->fix_rate, $distance_tarrif, $total_waiting_time_tarrif );

                $request->merge( [
                    'last_update_time' => Carbon::now(),
                    'total_tarrif' => $total_tarrif,
                    'distance_tarrif' => $distance_tarrif,
                    'waiting_tarrif' => $total_waiting_time_tarrif ,
                    'ride_distance' => $distance + $trip->ride_distance,
                    'ride_speed' => $speed ,
                    'total_waiting_time'=>$total_waiting_time,
                    'last_latitude' => $request->current_latitude,
                    'last_longitude' => $request->current_longitude,
                ] );
                $request->offsetUnset( 'current_latitude' );
                $request->offsetUnset( 'current_longitude' );
                return $this->success( 'Trip In Progress', $trip->tripInprogress( $request ) );
            } else {
                return $this->badrequest( 'Trip does not exists' );
            }
        }

        public function end( Trip $trip ) {
            if ( $trip->exists() ) {
                return $this->success( 'Trip Ended', $trip->end() );
            } else {
                return $this->badrequest( 'Trip does not exists' );
            }
        }

    }
