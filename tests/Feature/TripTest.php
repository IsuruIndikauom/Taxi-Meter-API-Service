<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Trip;
use App\Models\Tariff;
use Carbon\Carbon;

class TripTest extends TestCase {
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
            'role_id' => 3,
            'id'=>1,
        ] );
        $tarrif = Tariff::factory()->create();
        $response = $this->actingAs( $user )->post( 'api/trips/start', $this->data() );
        $this->assertCount( 1, Trip::all() );
        $trip = Trip::first();
        echo json_encode( $response->json(), JSON_PRETTY_PRINT );
        $response->assertJson( array_merge( $this->outputData( $trip ), [
            'message'=> 'Trip Started',
            'data'=>[
                'total_tarrif'=>'0.00',
                'ride_distance'=>'0.00',
                'waiting_tarrif'=> '0.00',
                'ride_speed'=> '0.00',
                'total_waiting_time'=> '0.00'
            ]
        ] ) );

        $this->assertEquals( $user->active_trip_id, $trip->id );
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
        $tarrif = Tariff::factory()->create();
        $response = $this->actingAs( $user )->post( 'api/trips/start', $this->data() );
        $response = $this->actingAs( $user )->post( 'api/trips/inprogress/'.$response->getData()->data->id, $this->inProgressData() );
        $this->assertCount( 1, Trip::all() );
        $trip = Trip::first();
        $response->assertJson( array_merge( $this->outputData( $trip ), [ 'message'=> 'Trip In Progress' ] ) );
        //echo $trip->toJson( JSON_PRETTY_PRINT );
        //echo json_encode( $response->json(), JSON_PRETTY_PRINT );

    }

    public function test_a_trip_can_be_ended(): void {
        $user = User::factory()->create( [
            'role_id' => 1,
            'id'=>1,
        ] );
        Tariff::factory()->create();
        $response = $this->actingAs( $user )->post( 'api/trips/start', $this->data() );
        $response = $this->actingAs( $user )->post( 'api/trips/end/'.$response->getData()->data->id, $this->data() );
        $this->assertCount( 1, Trip::all() );
        $trip = Trip::first();
        $response->assertJson( array_merge( $this->outputData( $trip ), [ 'message'=> 'Trip Ended' ] ) );
        $this->assertEquals( $trip->status, 0 );
        $this->assertEquals( $user->active_trip_id, null );
    }

    public function test_a_trip_can_be_wait_for_few_hours() {
        $user = User::factory()->create( [
            'role_id' => 1,
            'id'=>1,
        ] );
        $trip = Trip::factory()->create( [
            'last_update_time'=> Carbon::now()->subHours( 5 ),
            'user_id'=>$user->id,
            'status'=>1,
            'last_latitude' => 7.253195529141866,
            'last_longitude'=>  80.34525528285577,
            'total_waiting_time'=>  500,
            'ride_distance'=>  500.00,
        ] );
        $response = $this->actingAs( $user )->post( 'api/trips/inprogress/'.$trip->id, $this->inProgressData() );
        $trip = Trip::first();
        $response->assertJson( array_merge( $this->outputData( $trip ), [ 'message'=> 'Trip In Progress' ] ) );

    }

    public function data() {
        return [
            'start_latitude' => 7.253142671147482,
            'start_longitude'=> 80.34477474940532,
        ];
    }

    public function inProgressData() {
        return [
            'current_latitude' => 7.253195529141866,
            'current_longitude'=>  80.34525528285577,
        ];
        // small distance
        // return [
        //     'current_latitude' => 7.253140983852041,
        //     'current_longitude'=>  80.34477382633004,
        // ];
    }

    public function outputData( $trip ) {
        return [
            'message'=> 'Trip Ended',
            'data'=> [
                'id'=>$trip->id,
                'total_tarrif'=>$trip->total_tarrif,
                'ride_distance'=>$trip->ride_distance,
                'waiting_tarrif'=>$trip->waiting_tarrif,
                'ride_speed'=>$trip->ride_speed,
                'ride_speed'=>$trip->ride_speed,
                'total_waiting_time'=>sprintf( '%.2f', round( $trip->total_waiting_time/60, 2 ) ),
            ],
            'code'=> 200
        ];
    }
}
