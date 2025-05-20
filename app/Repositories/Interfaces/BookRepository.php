<?php

namespace App\Repositories\Interfaces;

use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;

interface BookRepository
{
    /**
     * Get all books
     */
    public function all(): Collection;

    /**
     * Get book by ID
     */
    public function find(int $id): ?Book;

    /**
     * Create a new book
     */
    public function create(array $data): Book;

    /**
     * Update an existing book
     */
    public function update(int $id, array $data): ?Book;

    /**
     * Delete a book
     */
    public function delete(int $id): bool;

    /**
     * Toggle the borrowed status of a book
     */
    public function toggleBorrowedStatus(int $id): ?Book;

    /**
     * Get books by author ID
     */
    public function getByAuthorId(int $authorId): Collection;
}
