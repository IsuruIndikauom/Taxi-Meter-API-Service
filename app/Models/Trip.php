<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Trip extends Model {
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'start_time' => 'datetime:Y-m-d H:i:s',
        'end_time' => 'datetime:Y-m-d H:i:s',
        'last_update_time' => 'datetime:Y-m-d H:i:s'
    ];

    public function startTrip( $data, $user ) {
        $tarrif = Tariff::where( 'status', 1 )->first();
        $data->merge( [
            'user_id' => $user->id ,
            'start_time' => Carbon::now(),
            'last_update_time' => Carbon::now(),
            'fix_rate' => $tarrif->fix_rate,
            'rate_per_km' => $tarrif->rate_per_km,
            'rate_per_minute' => $tarrif->rate_per_minute,
            'total_tarrif' => 0.00,
            'distance_tarrif' => 0.00,
            'waiting_tarrif' => 0.00,
            'total_waiting_time'=>0,
            'ride_distance' => 0.00,
            'ride_speed' => 0.00,
            'last_latitude' => $data->start_latitude,
            'last_longitude' => $data->start_longitude,
            'status' => 1
        ] );
        $trip = $this->create( $data->all() );
        $user->update( [ 'active_trip_id' => $trip->id ] );
        return $trip->tripResponse();
    }

    public function tripExistforUser( $user_id ) {
        return $this->where( 'user_id', $user_id )->where( 'status', 1 )->exists();

    }

    public function activeTripExists() {
        return $this->where( 'status', 1 )->exists();
    }

    public function tripInProgress( $data ) {
        $this->update( $data->all() );
        return $this->tripResponse();
    }

    public function end( $user ) {
        $this->update( [ 'status'=>0, 'ride_speed'=>0.00 ] );
        $user->update( [ 'active_trip_id' => NULL ] );
        return $this->tripResponse();
    }

    public function tripResponse() {
        $data = [
            'id'=>$this->id,
            'total_tarrif'=>sprintf( '%.2f', round( $this->total_tarrif, 2 ) ) ,
            'ride_distance'=>sprintf( '%.2f', round( $this->ride_distance, 2 ) ) ,
            'waiting_tarrif'=>sprintf( '%.2f', round( $this->waiting_tarrif, 2 ) ) ,
            'ride_speed'=>sprintf( '%.2f', round( $this->ride_speed, 2 ) ),
            'total_waiting_time'=>sprintf( '%.2f', round( $this->total_waiting_time/60, 2 ) ),
        ];
        return $data;
    }

    public function timeDiffInSeconds() {
        $current_time = Carbon::now();
        $interval = $current_time->diff( $this->last_update_time );
        $seconds = $interval->days * 24 * 60 * 60 + $interval->h * 60 * 60 + $interval->i * 60 + $interval->s;
        return $seconds;
    }

}
