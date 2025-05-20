<?php

namespace App\Repositories\Interfaces;

use App\Models\Author;
use Illuminate\Database\Eloquent\Collection;

interface AuthorRepository
{
    /**
     * Get all authors
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Get author by ID
     *
     * @param int $id
     * @return Author|null
     */
    public function find(int $id): ?Author;

    /**
     * Create a new author
     *
     * @param array $data
     * @return Author
     */
    public function create(array $data): Author;

    /**
     * Update an existing author
     *
     * @param int $id
     * @param array $data
     * @return Author|null
     */
    public function update(int $id, array $data): ?Author;

    /**
     * Delete an author
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}