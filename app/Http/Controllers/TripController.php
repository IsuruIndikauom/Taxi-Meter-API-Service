<?php

namespace App\Http\Controllers;

use App\Http\Requests\TripStartRequest;
use App\Models\Trip;
Use Auth;

class TripController extends Controller {
    public function start( TripStartRequest $request, Trip $trip ) {
        $user_id = Auth::User()->id;
        if ( $trip->tripExist( $user_id ) ) {
            return $this->badrequest( 'Trip exists for this user' );
        } else {
            return $this->success( 'Trip Started', $trip->startTrip( $request, $user_id ) );
        }
    }
}
