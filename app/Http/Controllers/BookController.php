<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Repositories\Interfaces\BookRepository;
use Illuminate\Http\JsonResponse;

class BookController extends Controller
{
    public function __construct(protected BookRepository $bookRepository)
    {
        $this->authorizeResource(Book::class);
    }

    public function index()
    {
        $books = $this->bookRepository->all();

        return response()->json($books->map(function ($book) {
            return (new BookResource($book))->toArray(request());
        }));
    }

    public function store(CreateBookRequest $request): BookResource
    {
        $book = $this->bookRepository->create($request->validated());

        $resource = new BookResource($book);
        $resource->response()->setStatusCode(201);

        return $resource;
    }

    public function show(Book $book): BookResource
    {
        return new BookResource($book);
    }

    public function update(UpdateBookRequest $request, Book $book): BookResource
    {
        $updatedBook = $this->bookRepository->update($book->id, $request->validated());

        return new BookResource($updatedBook);
    }

    public function destroy(Book $book): JsonResponse
    {
        $this->bookRepository->delete($book->id);

        return response()->json(['message' => 'Book deleted successfully']);
    }

    public function toggleBorrowedStatus(Book $book): BookResource
    {
        $this->authorize('toggleBorrowedStatus', $book);

        $userId = auth()->id();
        $updatedBook = $this->bookRepository->toggleBorrowedStatus($book->id, $userId);

        return new BookResource($updatedBook);
    }
}
