<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Tariff;
use App\Models\User;
use Tests\TestCase;

class TariffTest extends TestCase {
    use RefreshDatabase;
    /**
    * Test for user add
    */
    protected function setUp(): void {
        parent::setUp();
        $this->artisan( 'passport:install' );
        $this->withoutExceptionHandling();
    }

    public function test_tariff_can_be_added_to_system(): void {
        $user = User::factory()->create( [
            'role_id' => 1,
            'id'=>1,
        ] );
        $response = $this->actingAs( $user )->post( 'api/tariffs', $this->data() );
        $this->assertCount( 1, Tariff::all() );
        foreach ( $this->data() as $key => $value ) {
            $response->assertSee( $value );
        }
    }

    public function test_cannot_add_multiple_active_tariff_only_one_can_be_active(): void {
        $user = User::factory()->create( [
            'role_id' => 1,
            'id'=>1,
        ] );
        $response = $this->actingAs( $user )->post( 'api/tariffs', $this->data() );
        $response = $this->actingAs( $user )->post( 'api/tariffs', $this->data() );
        $this->assertCount( 2, Tariff::all() );
        $this->assertCount( 1, Tariff::where( 'status', 1 )->get() );
        foreach ( $this->data() as $key => $value ) {
            $response->assertSee( $value );
        }
    }

    // public function test_a_tarrif_can_be_updated(): void {
    //     $user = User::factory()->create( [
    //         'role_id' => 1,
    //         'id'=>1,
    // ] );
    //     $tarrif = Tariff::factory()->create();
    //     $response = $this->actingAs( $user )->patch( 'api/tariffs/'.$tarrif->id, $this->data() );
    //     $this->assertCount( 2, Tariff::all() );
    //     $this->assertCount( 1, Tariff::where( 'status', 1 )->get() );
    //     foreach ( $this->data() as $key => $value ) {
    //         $response->assertSee( $value );
    //     }
    // }

    public function data() {
        return [
            'fix_rate' => 100.00,
            'rate_per_km' => 50.00,
            'rate_per_minute' => 10.00,
            'status' => 1,
        ];
    }
}
