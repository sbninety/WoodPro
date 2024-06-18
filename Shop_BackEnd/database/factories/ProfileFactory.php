<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->numberBetween(1, 5),
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'city_id' => fake()->numberBetween(1, 2),
            'district_id' => fake()->numberBetween(1, 4),
            'address' => fake()->address()
        ];
    }
}
