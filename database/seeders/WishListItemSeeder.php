<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WishListItem;
use App\Models\User;
use App\Models\Advertisement;

class WishListItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all user IDs and advertisement IDs
        $userIds = User::pluck('id')->toArray();
        $advertisementIds = Advertisement::pluck('id')->toArray();

        // Generate 50 wish list items using the factory
        WishListItem::factory(50)->create([
            'user_id' => fn () => fake()->randomElement($userIds), // Random user ID
            'advertisement_id' => fn () => fake()->randomElement($advertisementIds), // Random advertisement ID
        ]);
    }
}
