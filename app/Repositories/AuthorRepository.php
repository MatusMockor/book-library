<?php

namespace App\Repositories;

use App\Models\Author;
use App\Repositories\Interfaces\AuthorRepository as AuthorRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AuthorRepository implements AuthorRepositoryInterface
{
    /**
     * Get all authors
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return Author::all();
    }

    /**
     * Get author by ID
     *
     * @param int $id
     * @return Author|null
     */
    public function find(int $id): ?Author
    {
        return Author::find($id);
    }

    /**
     * Create a new author
     *
     * @param array $data
     * @return Author
     */
    public function create(array $data): Author
    {
        return Author::create($data);
    }

    /**
     * Update an existing author
     *
     * @param int $id
     * @param array $data
     * @return Author|null
     */
    public function update(int $id, array $data): ?Author
    {
        $author = $this->find($id);
        
        if (!$author) {
            return null;
        }
        
        $author->update($data);
        
        return $author;
    }

    /**
     * Delete an author
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $author = $this->find($id);
        
        if (!$author) {
            return false;
        }
        
        return $author->delete();
    }
}