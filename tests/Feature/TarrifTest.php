<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Trip;
use App\Models\Tarrif;

class TarrifTest extends TestCase {
    /**
    * A basic feature test example.
    */
    use RefreshDatabase;

    protected function setUp(): void {
        parent::setUp();
        $this->artisan( 'passport:install' );
        $this->withoutExceptionHandling();
    }

    public function test_a_trip_can_be_started(): void {
        $user = User::factory()->create( [
            'role_id' => 1,
            'id'=>1,
        ] );
        $tarrif = Tarrif::factory()->create();
        $response = $this->actingAs( $user )->post( 'api/trips/start', $this->data() );
        $this->assertCount( 1, Trip::all() );
        $trip = Trip::first();
        $response->assertJson( [
            'message'=> 'Trip Started',
            'data'=> [
                'id'=>$trip->id,
                'total_tarrif'=>$trip->total_tarrif,
                'distance_tarrif'=>$trip->distance_tarrif,
                'waiting_tarrif'=>$trip->waiting_tarrif,
                'ride_speed'=>$trip->ride_speed,
            ],
            'code'=> 200
        ] );

    }

    public function test_a_trip_can_be_started_if_only_no_existing_trip_for_same_user(): void {
        $user = User::factory()->create( [
            'role_id' => 1,
            'id'=>1,
        ] );
        $trip = Trip::factory()->create( [
            'user_id' => $user->id,
            'status' =>1
        ] );
        $response = $this->actingAs( $user )->post( 'api/trips/start', $this->data() );
        $this->assertCount( 1, Trip::all() );
        $response->assertStatus( 400 );

    }

    public function test_a_trip_can_be_updated_with_coordinate_to_calcute_distance(): void {
        $user = User::factory()->create( [
            'role_id' => 1,
            'id'=>1,
        ] );
        $tarrif = Tarrif::factory()->create();
        $response = $this->actingAs( $user )->post( 'api/trips/start', $this->data() );
        $response = $this->actingAs( $user )->post( 'api/trips/inprogress/'.$response->getData()->data->id, $this->inProgressData() );
        $this->assertCount( 1, Trip::all() );
        $trip = Trip::first();
        $response->assertJson( [
            'message'=> 'Trip Started',
            'data'=> [
                'id'=>$trip->id,
                'total_tarrif'=>$trip->total_tarrif,
                'distance_tarrif'=>$trip->distance_tarrif,
                'waiting_tarrif'=>$trip->waiting_tarrif,
                'ride_speed'=>$trip->ride_speed,
            ],
            'code'=> 200
        ] );

    }

    public function data() {
        return [
            'start_latitude' => 7.253142671147482,
            'start_longitude'=> 80.34477474940532,
        ];
    }

    public function inProgressData() {
        // return [
        //     'current_latitude' => 7.253195529141866,
        //     'current_longitude'=>  80.34525528285577,
        // ];

        // small distance
        return [
            'current_latitude' => 7.253140983852041,
            'current_longitude'=>  80.34477382633004,
        ];

    }
}
