<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(fake()->numberBetween(4, 10));
        $status = fake()->randomElement(['draft', 'published', 'archived']);
        $published = $status === 'published'
            ? fake()->dateTimeBetween('-1 year', 'now')
            : null;

        return [
            'user_id' => User::factory(),
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'title' => rtrim($title, '.'),
            'slug' => Str::slug(rtrim($title, '.')).'-'.fake()->unique()->numberBetween(1, 99999),
            'excerpt' => fake()->optional(0.8)->paragraph(),
            'body' => implode("\n\n", fake()->paragraphs(fake()->numberBetween(3, 8))),
            'status' => $status,
            'is_featured' => fake()->boolean(15), // 15% chance featured
            'views_count' => fake()->numberBetween(0, 50000),
            'likes_count' => fake()->numberBetween(0, 5000),
            'published_at' => $published,
        ];
    }

    /**
     * Indicate that the post is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ]);
    }

    /**
     * Indicate that the post is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    /**
     * Indicate that the post is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }
}
