<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Repositories\Interfaces\BookRepository;
use Illuminate\Http\JsonResponse;

class BookController extends Controller
{
    public function __construct(protected BookRepository $bookRepository)
    {
        $this->authorizeResource(Book::class);
    }

    public function index(): JsonResponse
    {
        $books = $this->bookRepository->all();

        return response()->json($books);
    }

    public function store(CreateBookRequest $request): JsonResponse
    {
        $book = $this->bookRepository->create($request->validated());

        return response()->json($book, 201);
    }

    public function show(Book $book): JsonResponse
    {
        return response()->json($book);
    }

    public function update(UpdateBookRequest $request, Book $book): JsonResponse
    {
        $updatedBook = $this->bookRepository->update($book->id, $request->validated());

        return response()->json($updatedBook);
    }

    public function destroy(Book $book): JsonResponse
    {
        $this->bookRepository->delete($book->id);

        return response()->json(['message' => 'Book deleted successfully']);
    }

    public function toggleBorrowedStatus(Book $book): JsonResponse
    {
        $this->authorize('toggleBorrowedStatus', $book);

        $userId = auth()->id();
        $updatedBook = $this->bookRepository->toggleBorrowedStatus($book->id, $userId);

        return response()->json($updatedBook);
    }
}
