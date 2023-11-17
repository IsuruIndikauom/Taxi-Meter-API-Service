<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test for user add
     */
    public function test_a_user_can_be_added_to_the_system(): void
    {
        $this->withoutExceptionHandling();
        $response = $this->post('api/users', $this->data());
        $this->assertCount(1, User::all());
        foreach ($this->data() as $key => $value) {
            if ($key != "password") {
                $response->assertSee($value);
            }
        }

    }
    /**
     *\Checking password hash flow
     */
    public function test_password_is_hashed_when_user_is_created()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('api/users', $this->data());
        $user = User::first();
        // Assert that the password in the database is hashed
        $this->assertNotEquals(array_merge($this->data(), ['password' => 'password123'])['password'], $user->password);
        $this->assertTrue(Hash::check(array_merge($this->data(), ['password' => 'password123'])['password'], $user->password));
    }

    /**
     * Driver role id is 3
     * with only name and mobile number driver should be added to the system
     */
    public function test_a_driver_can_be_added_to_the_system(): void
    {
        $this->withoutExceptionHandling();
        $response = $this->post('api/users', array_merge($this->data(), ['role_id' => 3, 'address' => '', 'email' => '', 'password' => '']));
        $this->assertCount(1, User::all());
        foreach (array_merge($this->data(), ['role_id' => 3, 'address' => '', 'email' => '', 'password' => '']) as $key => $value) {
            if ($key != "password") {
                $response->assertSee($value);
            }
        }
    }
    /**
     * Developer and admin roles 1,2
     * with only name, mobile number, email and password developer and admin should be added to the system
     */
    public function test_a_developer_and_admin_can_be_added_to_the_system(): void
    {
        $this->withoutExceptionHandling();
        $response = $this->post('api/users', array_merge($this->data(), ['role_id' => 1, 'address' => '', 'email' => 'dev@taxi.com', 'password' => bcrypt('password123')]));
        $this->assertCount(1, User::all());
        foreach (array_merge($this->data(), ['role_id' => 1, 'address' => '', 'email' => 'dev@taxi.com', 'password' => '']) as $key => $value) {
            if ($key != "password") {
                $response->assertSee($value);
            }
        }
        $response = $this->post('api/users', array_merge($this->data(), ['role_id' => 2, 'mobile_number' => '0717196599', 'address' => '', 'email' => 'adminz@taxi.com', 'password' => bcrypt('password123')]));
        $this->assertCount(2, User::all());
        foreach (array_merge($this->data(), ['role_id' => 2, 'mobile_number' => '0717196599', 'address' => '', 'email' => 'adminz@taxi.com', 'password' => '']) as $key => $value) {
            if ($key != "password") {
                $response->assertSee($value);
            }
        }
    }

    public function test_a_developer_and_admin_cannot_be_added_to_the_system_without_email(): void
    {
        $response = $this->post('api/users', array_merge($this->data(), ['role_id' => 1, 'address' => '', 'email' => '', 'password' => bcrypt('password123')]));
        $response->assertSessionHasErrors('email');

        $response = $this->post('api/users', array_merge($this->data(), ['role_id' => 2, 'mobile_number' => '0717196599', 'address' => '', 'email' => 'abc@taxi.com', 'password' => '']));
        $response->assertSessionHasErrors('password');

    }

    public function data()
    {
        return [
            'name' => 'Isuru',
            'mobile_number' => '0717196590',
            'address' => 'Kegalle',
            'role_id' => 1,
            'email' => 'isuruindikauom@gmail.com',
            'password' => bcrypt('password123'),
        ];
    }
}
