<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => fake()->numberBetween(10000, 20000),
            'user_id' => fake()->numberBetween(1, 5),
            'city_id' => fake()->numberBetween(1, 2),
            'district_id' => fake()->numberBetween(1, 4),
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'total' => fake()->numberBetween(1000000, 100000000),
            'ship_method' => fake()->numberBetween(1, 2),
            'payment_method' => fake()->numberBetween(1, 2),
            'status' => fake()->numberBetween(1, 5)
        ];
    }
}
