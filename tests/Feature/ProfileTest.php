<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install');
        $this->withoutExceptionHandling();
    }

    public function test_to_get_current_user_profile_details(): void
    {
        $user = User::factory()->create([
            'role_id' => 3,
            'id' => 1,
        ]);
        $response = $this->actingAs($user)->get('api/profiles');
        $response->assertStatus(200);
        //echo json_encode($response->json(), JSON_PRETTY_PRINT);
        $response->assertJsonStructure([
            "message",
            "data" => [
                "user" => [
                    "name",
                    "status",
                    "role",
                ],
            ],
            "code",
        ]);

    }
}
