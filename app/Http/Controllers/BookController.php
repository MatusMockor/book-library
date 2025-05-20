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

    public function show(int $id): JsonResponse
    {
        $book = $this->bookRepository->find($id);

        if (! $book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        return response()->json($book);
    }

    public function update(UpdateBookRequest $request, int $id): JsonResponse
    {
        $book = $this->bookRepository->update($id, $request->validated());

        if (! $book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        return response()->json($book);
    }

    public function destroy(int $id): JsonResponse
    {
        $result = $this->bookRepository->delete($id);

        if (! $result) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        return response()->json(['message' => 'Book deleted successfully']);
    }

    public function toggleBorrowedStatus(int $id): JsonResponse
    {
        $book = $this->bookRepository->toggleBorrowedStatus($id);

        if (! $book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        return response()->json($book);
    }
}
