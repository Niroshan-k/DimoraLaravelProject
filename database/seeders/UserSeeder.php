<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 users with random data
        User::factory()->count(10)->create([
            'user_role' => 'buyer', // Set default user role to 'buyer'
        ]);

        // Create 5 sellers with random data
        User::factory()->count(5)->create([
            'user_role' => 'seller', // Set user role to 'seller'
        ]);
    }
}
