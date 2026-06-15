<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(fake()->numberBetween(1, 3), true);

        return [
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'color' => fake()->hexColor(),
            'description' => fake()->optional(0.7)->sentence(),
        ];
    }
}
