<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;

class UserManagementTest extends TestCase {
    use RefreshDatabase;
    /**
    * Test for user add
    */
    protected function setUp(): void {
        parent::setUp();

        // Install Passport for testing
        $this->artisan( 'passport:install' );

        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPasswordGrantClient(
            null, 'Test Password Client', 'http://localhost'
        );

        // Save client details for use in tests
        $this->clientId = $client->id;
        $this->clientSecret = $client->secret;

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

    public function test_a_user_name_can_be_logged_out():void {
        $user = User::factory()->create( [
            'role_id' => 3,
            'mobile_number' => '717196590',
            'name'=>''
        ] );
        $token = $user->createToken( 'TestToken' )->accessToken;
        $response = $this->withHeaders( [ 'Authorization' => 'Bearer ' . $token ] )
        ->post( '/api/logout' );

        // $response = $this->actingAs( $user )->post( 'api/logout' );
        $response->assertStatus( 200 );
    }

    public function test_a_user_name_cannot_be_logged_out_when_user_has_no_token():void {
        $user = User::factory()->create( [
            'role_id' => 3,
            'mobile_number' => '717196590',
            'name'=>''
        ] );
        $response = $this->actingAs( $user )->post( 'api/logout' );
        $response->assertStatus( 400 );
    }

    public function test_a_user_name_cannot_be_logged_out_without_valid_token():void {
        $user = User::factory()->create( [
            'role_id' => 3,
            'mobile_number' => '717196590',
            'name'=>''
        ] );
        $this->withoutExceptionHandling();
        $this->expectException( \Exception::class );
        $response = $this->post( 'api/logout' );
        $response->assertStatus( 401 );
    }

    public function test_a_user_can_be_viewed():void {
        $user = User::factory()->create( [
            'role_id' => 3,
            'mobile_number' => '717196590',
            'name'=>''
        ] );
        $response = $this->actingAs( $user )->get( 'api/users/'.$user->id );
        $response->assertStatus( 200 )->assertJson( [ 'message'=> 'User details', 'data' => $user->toArray() ] );
    }

    public function test_a_user_cannot_be_viewed_if_id_invalid():void {
        $user = User::factory()->create( [
            'role_id' => 3,
            'mobile_number' => '717196590',
            'id'=>1
        ] );
        $response = $this->actingAs( $user )->get( 'api/users/2' );
        $response->assertStatus( 404 );
    }

    public function test_all_users_can_be_view():void {
        $users = User::factory()->times( 10 )->create( [
            'role_id' => 3,
            'name'=>''
        ] );
        $response = $this->actingAs( $users->first() )->get( 'api/users' );
        $response->assertStatus( 200 )->assertJson( [ 'message'=> 'All users', 'data' => $users->toArray() ] );
    }

    public function test_a_user_can_be_deleted():void {
        $user = User::factory()->create( [
            'role_id' => 3,
            'mobile_number' => '717196590',
            'name'=>''
        ] );
        $response = $this->actingAs( $user )->delete( 'api/users/'.$user->id );
        $response->assertStatus( 200 )->assertJson( [ 'message'=> 'User deleted' ] );
        $this->assertCount( 0, User::all() );
    }

    public function test_a_user_cannot_be_deleted_for_invalid_id():void {
        $user = User::factory()->create( [
            'role_id' => 3,
            'mobile_number' => '717196590',
            'name'=>'',
            'id'=>1
        ] );
        $response = $this->actingAs( $user )->delete( 'api/users/2' );
        $response->assertStatus( 404 );
        $this->assertCount( 1, User::all() );
    }

    public function test_a_user_can_login_with_email_password_admin():void {
        $user = User::factory()->create( [
            'role_id' => 1,
            'id'=>1,
            'email'=>'test@test.com'
        ] );
        $response = $this->post( 'oauth/token', $this->loginData() );

        $response->assertStatus( 200 );
        $token = $response->json( 'access_token' );
    }

    public function test_a_user_cannot_login_with_invalid_email_correct_password_admin():void {
        $user = User::factory()->create( [
            'role_id' => 1,
            'id'=>1,
            'email'=>'test@test.com'
        ] );
        $response = $this->post( 'oauth/token', array_merge( $this->loginData(), [ 'username'=>'testif@test.com' ] ) );
        $response->assertStatus( 400 );
    }

    public function test_a_user_cannot_login_with_valid_email_invalid_password_admin():void {
        $user = User::factory()->create( [
            'role_id' => 1,
            'id'=>1,
            'email'=>'test@test.com'
        ] );
        $response = $this->post( 'oauth/token', array_merge( $this->loginData(), [ 'password'=>'123' ] ) );
        $response->assertStatus( 400 );
    }

    public function data() :array {
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

    public function loginData() :array {
        return [
            'grant_type' => 'password',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'username' => 'test@test.com',
            'password' => 'password',
            'scope' => '', ];
        }
    }
