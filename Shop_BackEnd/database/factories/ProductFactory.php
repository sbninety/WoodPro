<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => fake()->numberBetween(1, 8),
            'slug' => Str::slug(fake()->name()),
            'name' => fake()->name(),
            'price' => fake()->numberBetween(1000000, 100000000),
            'quantity' => fake()->numberBetween(1, 100),
            'description' => fake()->text(),
            'published' => '1'
        ];
    }
}
