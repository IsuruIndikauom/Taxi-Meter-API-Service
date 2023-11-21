<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\OTPCreateRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Contracts\CreateOTP;
use App\Models\OTP;
use App\Models\User;

class OTPController extends Controller {
    public function store( OTPCreateRequest $request, CreateOTP $otpService ) {
        $request->merge( [ 'otp' => $otpService->create() ] );
        $request->merge( [ 'mobile_number' => ltrim( $request->mobile_number, '0' ) ] );
        $opt = OTP::updateOrCreate(
            [ 'mobile_number' => $request->mobile_number ], // Conditions for matching existing record
            $request->all()  // Data to be updated or inserted
        );
        return $this->success( 'OTP Created', $opt->response() );
    }

    public function verify( VerifyOtpRequest $request, User $user ) {
        if ( $request->otp ==  OTP::where( 'mobile_number', $request->mobile_number )->first()->otp ) {
            if ( $user->checkUserExistOrNot( $request->mobile_number ) ) {
                return $this->success( 'OTP Verification', '' );
            } else {
                $request->merge( [ 'role_id' => 3 ] );
                $user = $user->createUser( $request );
            }
        }
        return $this->BAD_REQUEST( 'OTP Verification', '' );
    }
}
