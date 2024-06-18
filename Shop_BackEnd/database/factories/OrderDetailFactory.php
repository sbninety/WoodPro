<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OrderDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => fake()->numberBetween(1, 1000),
            'product_id' => fake()->numberBetween(1, 1000),
            'color_id' => fake()->numberBetween(1, 1000),
            'material_id' => fake()->numberBetween(1, 1000),
            'size_id' => fake()->numberBetween(1, 1000),
            'price' => fake()->numberBetween(1000000, 100000000),
            'quantity' => fake()->numberBetween(1, 5)
        ];
    }
}
