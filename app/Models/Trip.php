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
        'end_time' => 'datetime:Y-m-d H:i:s'
    ];

    public function startTrip( $data, $user_id ) {
        $tarrif = Tarrif::where( 'status', 1 )->first();
        $data->merge( [
            'user_id' => $user_id ,
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
        return $trip->tripResponse();
    }

    public function tripExist( $user_id ) {
        return $this->where( 'user_id', $user_id )->exists();

    }

    public function tripInProgress( $data ) {
        $this->update( $data->all() );
        return $this->tripResponse();
    }

    public function end() {
        $this->update( [ 'status'=>0 ] );
        return $this->tripResponse();
    }

    public function tripResponse() {
        $data = [
            'id'=>$this->id,
            'total_tarrif'=>$this->total_tarrif,
            'waiting_tarrif'=>$this->waiting_tarrif,
            'ride_speed'=>$this->ride_speed,
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
