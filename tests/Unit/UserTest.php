<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\DatabaseSeeder;

class UserTest extends TestCase {
    use RefreshDatabase;

    public function test_user_admin_seeder_with_all_running_seeder() {
        // Run the database seeder you want to test
        $this->seed( DatabaseSeeder::class );

        // Make assertions to verify the seeded data
        $this->assertDatabaseHas( 'users', [
            'name' => 'Admin User',
        ] );
    }
}
