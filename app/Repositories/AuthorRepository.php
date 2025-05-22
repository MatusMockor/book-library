<?php

namespace App\Repositories;

use App\Models\Author;
use App\Repositories\Interfaces\AuthorRepository as AuthorRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AuthorRepository implements AuthorRepositoryInterface
{
    /**
     * Get all authors
     */
    public function all(): Collection
    {
        return Author::all();
    }

    /**
     * Get author by ID
     */
    public function find(int $id): ?Author
    {
        return Author::find($id);
    }

    /**
     * Create a new author
     */
    public function create(array $data): Author
    {
        return Author::create($data);
    }

    /**
     * Update an existing author
     */
    public function update(Author $author, array $data): Author
    {
        $author->update($data);

        return $author;
    }

    /**
     * Delete an author
     */
    public function delete(Author $author): bool
    {
        return $author->delete();
    }
}
