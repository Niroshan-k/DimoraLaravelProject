<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Advertisement;
use App\Models\Property;
use App\Models\User;

class AdvertisementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all property IDs and user IDs
        //$propertyIds = Property::pluck('id')->toArray();
        $userIds = User::pluck('id')->toArray();

        // Generate 20 advertisements using the factory
        Advertisement::factory(20)->create([
            //'property_id' => fn () => fake()->randomElement($propertyIds), // Random property ID
            'seller_id' => fn () => fake()->randomElement($userIds), // Random user ID
        ]);
    }
}
