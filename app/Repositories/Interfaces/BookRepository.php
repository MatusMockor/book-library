<?php

namespace App\Repositories\Interfaces;

use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;

interface BookRepository
{
    public function all(): Collection;

    public function find(int $id): ?Book;

    public function create(array $data): Book;

    public function delete(Book $book): bool;

    public function toggleBookBorrowedStatus(Book $book, ?int $userId = null): Book;

    public function getByAuthorId(int $authorId): Collection;
}
