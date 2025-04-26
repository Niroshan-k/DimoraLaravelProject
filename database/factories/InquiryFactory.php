<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inquiry>
 */
class InquiryFactory extends Factory
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
            'user_id' => $this->faker->numberBetween(1, 100),
            'advertisement_id' => $this->faker->numberBetween(1, 100),
            'message' => $this->faker->sentence(10),
            'status' => $this->faker->randomElement(['pending', 'answered']),
        ];
    }
}
