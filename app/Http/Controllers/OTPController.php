<?php

namespace App\Http\Controllers;

use App\Http\Requests\OTPCreateRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Contracts\CreateOTP;
use App\Models\OTP;
use App\Models\User;
use Carbon\Carbon;

class OTPController extends Controller {
    public function store( OTPCreateRequest $request, CreateOTP $otpService, OTP $otp ) {
        return $this->success( 'OTP Created', $otp->createOTP( $request, $otpService->create() ) );
    }

    public function verify( VerifyOtpRequest $request, User $user ) {
        if ( OTP::where( 'mobile_number', $request->mobile_number )->first() != null && $request->otp ==  OTP::where( 'mobile_number', $request->mobile_number )->first()->otp ) {
            $expiresAt = Carbon::now()->addDays( 3 );
            $exist_user = $user->checkUserExistOrNot( $request->mobile_number );
            if ( $exist_user !=  null ) {
                $token = $exist_user->createToken( 'Taxi', [ '*' ],  $expiresAt )->accessToken;
                return $this->success( 'OTP Verification', [ 'token'=>$token, 'new_user' => false ] );
            } else {
                $request->merge( [ 'role_id' => 3 ] );
                $user = $user->createUser( $request );
                $token = $user->createToken( 'Taxi', [ '*' ],  $expiresAt )->accessToken;
                return $this->success( 'OTP Verification', [ 'token'=>$token, 'new_user' => true, 'user_id'=>$user->id ] );
            }
        }
        return $this->badRequest( 'OTP Verification Failed', '' );
    }
}
