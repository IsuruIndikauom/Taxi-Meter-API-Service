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
    }

    public function data() {
        return [
            'country_code' => 'Isuru',
            'mobile_number' => '0717196590',
        ];
    }
}
