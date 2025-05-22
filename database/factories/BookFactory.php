<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'author_id' => Author::factory(),
            'is_borrowed' => fake()->boolean(20), // 20% chance of being borrowed
            'borrowed_by' => null,
        ];
    }

    /**
     * Indicate that the book is borrowed by a specific user.
     */
    public function borrowedBy($userId): static
    {
        return $this->state(fn (array $attributes) => [
            'is_borrowed' => true,
            'borrowed_by' => $userId,
        ]);
    }
}
