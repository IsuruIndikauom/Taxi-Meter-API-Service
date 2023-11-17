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
