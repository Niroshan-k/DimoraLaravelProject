<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'location' => $this->faker->address(),
            'price' => $this->faker->numberBetween(100000, 10000000),
            'type' => $this->faker->randomElement(['house', 'apartment', 'land']),
            'advertisement_id' => $this->faker->numberBetween(1, 100),
        ];
    }
}
