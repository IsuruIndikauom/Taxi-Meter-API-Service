<?php

namespace Tests\Feature;

use App\Models\Tariff;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingTest extends TestCase
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

    public function test_setting_can_be_view_with_authenticated_user(): void
    {
        $user = User::factory()->create([
            'role_id' => 1,
            'id' => 1,
        ]);
        $tarrif = Tariff::factory()->create();
        $response = $this->actingAs($user)->get('api/settings');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "message",
            "data" => [
                "tariff" => [
                    "id",
                    "fix_rate",
                    "rate_per_km",
                    "rate_per_minute",
                    "status",
                    "created_at",
                    "updated_at",
                ],
            ],
            "code",
        ]);

    }
}
