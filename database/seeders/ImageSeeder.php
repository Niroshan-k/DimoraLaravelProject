<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Image;
use App\Models\Advertisement;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all advertisement IDs
        $advertisementIds = Advertisement::pluck('id')->toArray();

        // Generate 20 images, each linked to a random advertisement
        Image::factory(20)->create([
            'advertisement_id' => fn () => fake()->randomElement($advertisementIds),
        ]);
    }
}