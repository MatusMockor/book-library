<?php

namespace App\Repositories;

use App\Models\Book;
use App\Repositories\Interfaces\BookRepository as BookRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class BookRepository implements BookRepositoryInterface
{
    /**
     * Get all books
     */
    public function all(): Collection
    {
        return Book::with(['author', 'borrower'])->get();
    }

    /**
     * Get book by ID
     */
    public function find(int $id): ?Book
    {
        return Book::with(['author', 'borrower'])->find($id);
    }

    /**
     * Create a new book
     */
    public function create(array $data): Book
    {
        return Book::create($data);
    }

    /**
     * Update an existing book using Book model
     */
    public function update(Book $book, array $data): Book
    {
        $book->update($data);

        return $book;
    }

    /**
     * Delete a book using Book model
     */
    public function delete(Book $book): bool
    {
        return $book->delete();
    }

    public function toggleBookBorrowedStatus(Book $book, ?int $userId = null): Book
    {
        $book->is_borrowed = ! $book->is_borrowed;

        if ($book->is_borrowed) {
            // Book is being borrowed
            $book->borrowed_by = $userId;
        } else {
            // Book is being returned
            $book->borrowed_by = null;
        }

        $book->save();

        return $book;
    }

    /**
     * Get books by author ID
     */
    public function getByAuthorId(int $authorId): Collection
    {
        return Book::where('author_id', $authorId)->get();
    }
}
