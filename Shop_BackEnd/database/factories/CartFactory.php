<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CartFactory extends Factory
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
            'product_id' => fake()->numberBetween(1, 1000),
            'color_id' => fake()->numberBetween(1, 1000),
            'material_id' => fake()->numberBetween(1, 1000),
            'size_id' => fake()->numberBetween(1, 1000),
            'quantity' => fake()->numberBetween(1, 5)
        ];
    }
}
