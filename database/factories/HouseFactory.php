<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\House>
 */
class HouseFactory extends Factory
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
            'bedroom' => $this->faker->numberBetween(1, 5),
            'bathroom' => $this->faker->numberBetween(1, 3),
            'pool' => $this->faker->boolean(),
            'area' => $this->faker->numberBetween(50, 500),
            'parking' => $this->faker->boolean(),
            'property_id' => $this->faker->numberBetween(1, 100),
            'house_type' => $this->faker->randomElement(['luxury', 'modern', 'traditional']),
        ];
    }
}
