<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SizeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => fake()->numberBetween(1, 1000),
            'length' => fake()->numberBetween(100, 200),
            'width' => fake()->numberBetween(100, 200),
            'height' => fake()->numberBetween(100, 200),
            'price' => fake()->numberBetween(1000000, 100000000)
        ];
    }
}
