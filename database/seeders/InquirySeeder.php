<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inquiry;
use App\Models\User;
use App\Models\Advertisement;

class InquirySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all user IDs and advertisement IDs
        $userIds = User::pluck('id')->toArray();
        $advertisementIds = Advertisement::pluck('id')->toArray();

        // Check if there are valid user IDs and advertisement IDs
        if (empty($userIds) || empty($advertisementIds)) {
            $this->command->warn('No users or advertisements found. Skipping InquirySeeder.');
            return;
        }

        // Generate 50 inquiries using the factory
        Inquiry::factory(50)->create([
            'user_id' => fn () => fake()->randomElement($userIds), // Random user ID
            'advertisement_id' => fn () => fake()->randomElement($advertisementIds), // Random advertisement ID
            'status' => fn () => fake()->randomElement(['pending', 'responded']), // Valid statuses
        ]);
    }
}