<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property;
use App\Models\Advertisement;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all advertisement IDs
        $advertisementIds = Advertisement::pluck('id')->toArray();
        // Generate 20 properties using the factory
        Property::factory(20)->create([
            'advertisement_id' => fn () => fake()->randomElement($advertisementIds), // Random advertisement ID
        ]);
    }
}
