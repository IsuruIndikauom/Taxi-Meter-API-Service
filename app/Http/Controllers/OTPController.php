<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\OTPCreateRequest;
use App\Contracts\CreateOTP;
use App\Models\OTP;

class OTPController extends Controller {
    public function store( OTPCreateRequest $request, CreateOTP $otpService ) {
        $request->merge( [ 'otp' => $otpService->create() ] );
        $request->merge( [ 'mobile_number' => ltrim( $request->mobile_number, '0' ) ] );

        $opt = OTP::create( $request->all() );
        return $this->success( 'OTP Created', $opt->response() );
    }
}
