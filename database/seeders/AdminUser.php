<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUser extends Seeder {
    /**
    * Run the database seeds.
    */

    public function run(): void {

        \App\Models\User::factory()->create( [
            'name' => 'Admin User',
            'email' => 'isuruindikauom@gmail.com',
            'password' => '123',
            'role_id'=>1,
        ] );
    }
}
