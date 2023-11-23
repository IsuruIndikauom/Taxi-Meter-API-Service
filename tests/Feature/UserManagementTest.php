<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Laravel\Passport\Passport;

class UserManagementTest extends TestCase {
    use RefreshDatabase;
    /**
    * Test for user add
    */
    protected function setUp(): void {
        parent::setUp();
        $this->artisan( 'passport:install' );
        //$this->withoutExceptionHandling();
    }

    public function test_a_user_can_be_added_to_the_system(): void {
        $user = User::factory()->create( [
            'role_id' => 1,
            'id'=>1,
        ] );
        $response = $this->actingAs( $user )->post( 'api/users', $this->data() );
        $this->assertCount( 2, User::all() );
        foreach ( $this->data() as $key => $value ) {
            if ( $key != 'password' ) {
                $response->assertSee( $value );
            }
        }
    }
    /**
    *\Checking password hash flow
    */

    public function test_password_is_hashed_when_user_is_created() {
        $user = User::factory()->create( [
            'role_id' => 1,
            'id'=>1
        ] );
        $response = $this->actingAs( $user )->post( 'api/users', $this->data() );
        $this->assertCount( 2, User::all() );
        $user = User::where( 'mobile_number', '717196590 ' )->first();
        // Assert that the password in the database is hashed
        $this->assertNotEquals( array_merge( $this->data(), [ 'password' => 'password123' ] )[ 'password' ], $user->password );
        $this->assertTrue( Hash::check( array_merge( $this->data(), [ 'password' => 'password123' ] )[ 'password' ], $user->password ) );
    }

    /**
    * Driver role id is 3
    * with only name and mobile number driver should be added to the system
    */

    public function test_a_driver_can_be_added_to_the_system(): void {
        $user = User::factory()->create( [
            'role_id' => 1,
            'id'=>1
        ] );
        $response = $this->actingAs( $user )->post( 'api/users',  array_merge( $this->data(), [ 'role_id' => 3, 'address' => '', 'email' => '', 'password' => '' ] ) );
        $this->assertCount( 2, User::all() );
        foreach ( array_merge( $this->data(), [ 'role_id' => 3, 'address' => '', 'email' => '', 'password' => '' ] ) as $key => $value ) {
            if ( $key != 'password' ) {
                $response->assertSee( $value );
            }
        }
    }
    /**
    * Developer and admin roles 1, 2
    * with only name, mobile number, email and password developer and admin should be added to the system
    */

    public function test_a_developer_and_admin_can_be_added_to_the_system(): void {
        $user = User::factory()->create( [
            'role_id' => 1,
            'id'=>1
        ] );
        $response = $this->actingAs( $user )->post( 'api/users', array_merge( $this->data(), [ 'role_id' => 1, 'address' => '', 'email' => 'dev@taxi.com', 'password' => bcrypt( 'password123' ) ] ) );
        $this->assertCount( 2, User::all() );
        foreach ( array_merge( $this->data(), [ 'role_id' => 1, 'address' => '', 'email' => 'dev@taxi.com', 'password' => '' ] ) as $key => $value ) {
            if ( $key != 'password' ) {
                $response->assertSee( $value );
            }
        }
        $response = $this->actingAs( $user )->post( 'api/users', array_merge( $this->data(), [ 'role_id' => 2, 'mobile_number' => '0717196599', 'address' => '', 'email' => 'adminz@taxi.com', 'password' => bcrypt( 'password123' ) ] ) );
        $this->assertCount( 3, User::all() );
        foreach ( array_merge( $this->data(), [ 'role_id' => 2, 'mobile_number' => '717196599', 'address' => '', 'email' => 'adminz@taxi.com', 'password' => '' ] ) as $key => $value ) {
            if ( $key != 'password' ) {
                $response->assertSee( $value );
            }
        }
    }

    public function test_a_developer_and_admin_cannot_be_added_to_the_system_without_email(): void {
        $user = User::factory()->create( [
            'role_id' => 1,
            'id'=>1
        ] );
        $response = $this->actingAs( $user )->post( 'api/users', array_merge( $this->data(), [ 'role_id' => 1, 'address' => '', 'email' => '', 'password' => bcrypt( 'password123' ) ] ) );
        //$response = $this->post( 'api/users', array_merge( $this->data(), [ 'role_id' => 1, 'address' => '', 'email' => '', 'password' => bcrypt( 'password123' ) ] ) );
        $response->assertSessionHasErrors( 'email' );

        $response = $this->actingAs( $user )->post( 'api/users', array_merge( $this->data(), [ 'role_id' => 2, 'address' => '', 'email' => '', 'password' => bcrypt( 'password123' ) ] ) );
        $response->assertSessionHasErrors( 'email' );

    }

    public function test_leading_0_should_remove_when_create_user(): void {
        $user = User::factory()->create( [
            'role_id' => 1,
            'id'=>1
        ] );
        $response = $this->actingAs( $user )->post( 'api/users', array_merge( $this->data(), [ 'mobile_number' => '0717196590' ] ) );
        $this->assertCount( 2, User::all() );
        $this->assertEquals( '717196590', User::where( 'email', 'isuruindikauom@gmail.com' )->first()->mobile_number );
    }

    public function test_a_user_can_be_updated():void {
        $user = User::factory()->create( [
            'role_id' => 3,
            'mobile_number' => '717196590',
        ] );
        $response = $this->actingAs( $user )->patch( 'api/users/'.$user->id, $this->data() );
        $this->assertCount( 1, User::all() );
        foreach ( $this->data() as $key => $value ) {
            if ( $key != 'password' ) {
                $response->assertSee( $value );
            }
        }
    }

    public function test_a_user_name_can_be_updated():void {
        $user = User::factory()->create( [
            'role_id' => 3,
            'mobile_number' => '717196590',
            'name'=>''
        ] );
        $this->assertEquals( $user->name, null );
        $response = $this->actingAs( $user )->patch( 'api/users/'.$user->id, [ 'name'=>'Isuru' ] );
        $response->assertSee( 'Isuru' );
        $this->assertCount( 1, User::all() );
    }

    public function data() {
        return [
            'name' => '',
            'mobile_number' => '717196590',
            'country_code'=>'+94',
            'address' => 'Kegalle',
            'role_id' => 1,
            'email' => 'isuruindikauom@gmail.com',
            'password' => bcrypt( 'password123' ),
        ];
    }
}
