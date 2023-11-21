<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\OTPCreateRequest;
use App\Models\OTP;

class OTPController extends Controller {
    public function store( OTPCreateRequest $request ) {
        $user = OTP::create( $request->all() );
        return response()->json( $user );
    }
}
