<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all authors (or create some if none exist)
        $authors = Author::all();

        if ($authors->isEmpty()) {
            // Create authors if none exist
            $authors = Author::factory(5)->create();
        }

        // Create 1-5 books for each author
        foreach ($authors as $author) {
            $booksCount = fake()->numberBetween(1, 5);

            Book::factory($booksCount)
                ->create([
                    'author_id' => $author->id,
                ]);
        }
    }
}
