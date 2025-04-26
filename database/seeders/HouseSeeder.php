<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\House;
use App\Models\Property;

class HouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

     
    public function run(): void
    {
        $propertyIds = Property::pluck('id')->toArray();
        // Generate 20 houses using the factory
        House::factory(20)->create([
            'property_id' => fn () => fake()->randomElement($propertyIds),
        ]);
    }
}

        
        