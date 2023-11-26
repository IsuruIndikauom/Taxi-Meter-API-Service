<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Tarrif;
use App\Models\User;
use Tests\TestCase;

class TarrifTest extends TestCase {
    use RefreshDatabase;
    /**
    * Test for user add
    */
    protected function setUp(): void {
        parent::setUp();
        $this->artisan( 'passport:install' );
        $this->withoutExceptionHandling();
    }

    public function test_tarrif_can_be_added_to_system(): void {
        $user = User::factory()->create( [
            'role_id' => 1,
            'id'=>1,
        ] );
        $response = $this->actingAs( $user )->post( 'api/tarrifs', $this->data() );
        $this->assertCount( 1, Tarrif::all() );
        foreach ( $this->data() as $key => $value ) {
            $response->assertSee( $value );
        }
    }

    public function test_cannot_add_multiple_active_tarrif_only_one_can_be_active(): void {
        $user = User::factory()->create( [
            'role_id' => 1,
            'id'=>1,
        ] );
        $response = $this->actingAs( $user )->post( 'api/tarrifs', $this->data() );
        $response = $this->actingAs( $user )->post( 'api/tarrifs', $this->data() );
        $this->assertCount( 2, Tarrif::all() );
        $this->assertCount( 1, Tarrif::where( 'status', 1 )->get() );
        foreach ( $this->data() as $key => $value ) {
            $response->assertSee( $value );
        }
    }

    public function data() {
        return [
            'fix_rate' => 100.00,
            'rate_per_km' => 50.00,
            'rate_per_minute' => 10.00,
            'status' => 1,
        ];
    }
}
