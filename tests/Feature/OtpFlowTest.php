<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\OTP;
use App\Models\User;

class OtpFlowTest extends TestCase {

    use RefreshDatabase;
    /**
    * A basic feature test example.
    */
    protected function setUp(): void {
        parent::setUp();
        $this->artisan( 'passport:install' );
        $this->withoutExceptionHandling();
    }

    public function test_create_otp_with_mobile_number(): void {
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

    public function test_a_otp_can_be_verify_for_new_user() {
        $response = $this->post( 'api/otps', $this->data() );
        $response = $this->post( 'api/otps/verify', $this->otp_data() );
        $this->assertCount( 1, User::all() );
        $response->assertJson( [
            'message'=> 'OTP Verification',
            'data'=> [
                'new_user'=>true
            ],
            'code'=> 200
        ] );

    }

    public function test_a_otp_can_be_verify_for_exiting_user() {
        User::factory()->create( [
            'role_id' => 3,
            'mobile_number' => '717196590',
        ] );
        $response = $this->post( 'api/otps', $this->data() );
        $response = $this->post( 'api/otps/verify', $this->otp_data() );
        $this->assertCount( 1, User::all() );
        $response->assertJson( [
            'message'=> 'OTP Verification',
            'data'=> [
                'new_user'=>false
            ]
            ,
            'code'=> 200
        ] );

    }

    public function test_a_invalide_otp_cannot_proceed() {
        User::factory()->create( [
            'role_id' => 3,
            'mobile_number' => '717196590',
        ] );
        $response = $this->post( 'api/otps', $this->data() );
        $response = $this->post( 'api/otps/verify', array_merge( $this->otp_data(), [ 'otp'=>'1111' ] ) );
        $this->assertCount( 1, User::all() );
        $response->assertJson( [
            'message'=> 'OTP Verification Failed',
            'data'=> '',
            'code'=> 400
        ] );
        $response->assertStatus( 400 );

    }

    public function test_a_otp_cannot_verify_without_creating_one() {
        $response = $this->post( 'api/otps/verify', $this->otp_data() );
        $response->assertJson( [
            'message'=> 'OTP Verification Failed',
            'data'=> '',
            'code'=> 400
        ] );
        $response->assertStatus( 400 );

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
