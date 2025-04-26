<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder; // Import the UserSeeder
use Database\Seeders\AdvertisementSeeder; // Import the AdvertisementSeeder
use Database\Seeders\PropertySeeder; // Import the PropertySeeder
use Database\Seeders\HouseSeeder; // Import the HouseSeeder
use Database\Seeders\ImageSeeder; // Import the ImageSeeder
use Database\Seeders\InquirySeeder; // Import the InquirySeeder
use Database\Seeders\NotificationSeeder; // Import the NotificationSeeder
use Database\Seeders\WishListItemSeeder; // Import the WishListItemSeeder

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AdvertisementSeeder::class,
            PropertySeeder::class,
            HouseSeeder::class, // Added HouseSeeder
            ImageSeeder::class, // Added ImageSeeder
            InquirySeeder::class, // Added InquirySeeder
            NotificationSeeder::class, // Added NotificationSeeder
            WishListItemSeeder::class, // Added WishListItemSeeder
        ]);
    }
}
