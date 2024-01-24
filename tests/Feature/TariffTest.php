<?php

namespace Tests\Feature;

use App\Models\Tariff;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TariffTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test for user add
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install');
        $this->withoutExceptionHandling();
    }

    public function test_tariff_can_be_added_to_system(): void
    {
        $user = User::factory()->create([
            'role_id' => 1,
            'id' => 1,
        ]);
        $response = $this->actingAs($user)->post('api/tariffs', $this->data());
        $this->assertCount(1, Tariff::all());
        foreach ($this->data() as $key => $value) {
            $response->assertSee($value);
        }
    }

    public function test_cannot_add_multiple_active_tariff_only_one_can_be_active(): void
    {
        $user = User::factory()->create([
            'role_id' => 1,
            'id' => 1,
        ]);
        $response = $this->actingAs($user)->post('api/tariffs', $this->data());
        $response = $this->actingAs($user)->post('api/tariffs', $this->data());
        $this->assertCount(2, Tariff::all());
        $this->assertCount(1, Tariff::where('status', 1)->get());
        foreach ($this->data() as $key => $value) {
            $response->assertSee($value);
        }
    }

    public function test_a_tariff_can_be_updated_with_status_1(): void
    {
        $user = User::factory()->create([
            'role_id' => 1,
            'id' => 1,
        ]);
        $tarrif = Tariff::factory()->create();
        $response = $this->actingAs($user)->patch('api/tariffs/' . $tarrif->id, $this->data());
        $this->assertCount(1, Tariff::all());
        $this->assertCount(1, Tariff::where('status', 1)->get());
        foreach ($this->data() as $key => $value) {
            $response->assertSee($value);
        }
    }

    public function test_a_tariff_can_be_updated_with_status_0(): void
    {
        $user = User::factory()->create([
            'role_id' => 1,
            'id' => 1,
        ]);
        $tarrif = Tariff::factory()->create();
        $response = $this->actingAs($user)->patch('api/tariffs/' . $tarrif->id, array_merge($this->data(), ['status' => 0]));
        $this->assertCount(1, Tariff::all());
        $this->assertCount(1, Tariff::where('status', 1)->get());
        foreach ($this->data() as $key => $value) {
            $response->assertSee($value);
        }
    }

    public function test_a_tariff_can_be_viewed(): void
    {
        $user = User::factory()->create([
            'role_id' => 1,
            'id' => 1,
        ]);
        $tarrif = Tariff::factory()->create();
        $response = $this->actingAs($user)->get('api/tariffs/' . $tarrif->id);
        $response->assertStatus(200)->assertJson(['message' => 'Tariff details', 'data' => $tarrif->toArray()]);
    }

    public function test_all_tariff_can_be_viewed(): void
    {
        $user = User::factory()->create([
            'role_id' => 1,
            'id' => 1,
        ]);
        $tarrifs = Tariff::factory()->times(20)->create();
        $response = $this->actingAs($user)->get('api/tariffs');
        $response->assertStatus(200)->assertJson(['message' => 'All tariffs', 'data' => $tarrifs->toArray()]);
    }

    public function test_a_tariff_can_be_deleted(): void
    {
        $user = User::factory()->create([
            'role_id' => 1,
            'id' => 1,
        ]);
        $tarrif = Tariff::factory()->create();
        $response = $this->actingAs($user)->delete('api/tariffs/' . $tarrif->id);
        $this->assertCount(0, Tariff::all());
        $response->assertStatus(200)->assertJson(['message' => 'Tariff deleted']);
    }

    public function data()
    {
        return [
            'fix_rate' => 100.00,
            'rate_per_km' => 50.00,
            'rate_per_minute' => 10.00,
            'status' => 1,
        ];
    }
}
