<?php

namespace App\Http\Controllers;

use App\Contracts\CalculateDistance;
use App\Contracts\CalculateDistanceTariff;
use App\Contracts\CalculateSpeed;
use App\Contracts\CalculateTotalTariff;
use App\Contracts\CalculateWaiting;
use App\Http\Requests\EndTripV2Request;
use App\Http\Requests\TripInProgressRequest;
use App\Http\Requests\TripStartRequest;
use App\Models\Trip;
use Auth;
use Carbon\Carbon;

class TripController extends Controller
{
    public function start(TripStartRequest $request, Trip $trip)
    {
        $user = Auth::User();
        if ($trip->tripExistforUser($user->id)) {
            return $this->badRequest('Trip exists for this user');
        } else {
            return $this->success('Trip Started', $trip->startTrip($request, $user));
        }
    }

    public function startV2(Trip $trip)
    {
        $user = Auth::User();
        if ($trip->tripExistforUser($user->id)) {
            return $this->badRequest('Trip exists for this user');
        } else {
            return $this->success('Trip Started', $trip->startTripV2($user));
        }
    }

    public function inProgress(
        TripInProgressRequest $request,
        Trip $trip,
        CalculateDistance $distance,
        CalculateWaiting $total_waiting,
        CalculateSpeed $speed,
        CalculateDistanceTariff $distance_tariff,
        CalculateTotalTariff $total_tariff) {
        if ($trip->activeTripExists()) {
            $distance = $distance->distanceInMetres($trip->last_latitude, $trip->last_longitude, $request->current_latitude, $request->current_longitude);
            $total_waiting_time = $total_waiting->totalWaitingTime($trip->timeDiffInSeconds(), $trip->total_waiting_time, $distance);
            $total_waiting_time_tariff = $total_waiting->totalWaitingTimeTariff($total_waiting_time, $trip->rate_per_minute);
            $speed = $speed->speedInKMPH($trip->timeDiffInSeconds(), $distance);
            $distance_tariff = $distance_tariff->distanceTariff($distance + $trip->ride_distance, $trip->rate_per_km);
            $total_tariff = $total_tariff->total($trip->fix_rate, $distance_tariff, $total_waiting_time_tariff);
            $request->merge([
                'last_update_time' => Carbon::now(),
                'total_tarrif' => $total_tariff,
                'distance_tarrif' => $distance_tariff,
                'waiting_tarrif' => $total_waiting_time_tariff,
                'ride_distance' => sprintf('%.2f', round(($distance + $trip->ride_distance), 2)),
                'ride_speed' => $speed,
                'total_waiting_time' => $total_waiting_time,
                'last_latitude' => $request->current_latitude,
                'last_longitude' => $request->current_longitude,
            ]);
            $request->offsetUnset('current_latitude');
            $request->offsetUnset('current_longitude');
            return $this->success('Trip In Progress', $trip->tripInprogress($request));
        } else {
            return $this->badRequest('Active trip does not exists');
        }
    }

    public function end(Trip $trip)
    {
        $user = Auth::User();
        if ($trip->activeTripExists()) {
            return $this->success('Trip Ended', $trip->end($user));
        } else {
            return $this->badRequest('Active trip does not exists');
        }
    }

    public function endV2(EndTripV2Request $request, Trip $trip)
    {
        $user = Auth::User();
        if ($trip->activeTripExists()) {
            return $this->success('Trip Ended', $trip->endV2($request, $user));
        } else {
            return $this->badRequest('Active trip does not exists');
        }
    }

    public function getAll(Trip $trip)
    {
        return $this->success('All Trips', $trip->getAll());
    }

    public function getUserAll(Trip $trip)
    {
        $user = Auth::User();
        return $this->success('Current User All Trips', $trip->getUserAll($user->id));
    }
}
