<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\OTP;

class OtpFlowTest extends TestCase {

    use RefreshDatabase;
    /**
    * A basic feature test example.
    */

    public function test_create_otp_with_mobile_number(): void {
        $this->withoutExceptionHandling();
        $response = $this->post( 'api/otps', $this->data() );
        $this->assertCount( 1, OTP::all() );
        $this->assertEquals( '717196590', OTP::first()->mobile_number );
        $response->assertSee( OTP::first()->otp );
        $response->assertJson( [
            'message'=> 'OTP Created',
            'data'=> [
                'otp'=> OTP::first()->otp,
                'mobile_number'=> OTP::first()->mobile_number
            ]
            ,
            'code'=> 200
        ] );

    }

    public function test_create_otp_with_mobile_number_without_leading_0(): void {
        $this->withoutExceptionHandling();
        $response = $this->post( 'api/otps', array_merge( $this->data(), [ 'mobile_number' => '717196590' ] ) );
        $this->assertCount( 1, OTP::all() );
        $this->assertEquals( '717196590', OTP::first()->mobile_number );
        $response->assertSee( OTP::first()->otp );
        $response->assertJson( [
            'message'=> 'OTP Created',
            'data'=> [
                'otp'=> OTP::first()->otp,
                'mobile_number'=> OTP::first()->mobile_number
            ]
            ,
            'code'=> 200
        ] );

    }

    public function test_a_otp_can_be_verify() {
        $this->withoutExceptionHandling();
        $response = $this->post( 'api/otps', $this->data() );
        $response = $this->post( 'api/otps/verify', $this->otp_data() );
        $response->assertJson( [
            'message'=> 'OTP Verification',
            'data'=> [
                'status'=> true,
                'mobile_number'=> OTP::first()->mobile_number,
                'access_token' =>'ss'
            ]
            ,
            'code'=> 200
        ] );

    }

    public function data() {
        return [
            'country_code' => '+94',
            'mobile_number' => '0717196590',
        ];
    }

    public function otp_data() {
        return [
            'otp' => '0000',
            'mobile_number' => '717196590',
        ];
    }

}
