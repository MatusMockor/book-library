<?php

namespace App\Repositories\Interfaces;

use App\Models\Author;
use Illuminate\Database\Eloquent\Collection;

interface AuthorRepository
{
    /**
     * Get all authors
     */
    public function all(): Collection;

    /**
     * Get author by ID
     */
    public function find(int $id): ?Author;

    /**
     * Create a new author
     */
    public function create(array $data): Author;

    /**
     * Update an existing author
     */
    public function update(int $id, array $data): ?Author;

    /**
     * Delete an author
     */
    public function delete(int $id): bool;
}
