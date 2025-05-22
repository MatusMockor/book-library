<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Models\Author;
use App\Repositories\Interfaces\AuthorRepository;
use Illuminate\Http\JsonResponse;

class AuthorController extends Controller
{
    protected $authorRepository;

    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
        $this->authorizeResource(Author::class);
    }

    public function index(): JsonResponse
    {
        $authors = $this->authorRepository->all();

        // Add book count to each author
        $authors->each(function ($author) {
            $author->book_count = $author->books->count();
        });

        return response()->json($authors);
    }

    public function store(CreateAuthorRequest $request): JsonResponse
    {
        $author = $this->authorRepository->create($request->validated());

        return response()->json($author, 201);
    }

    public function show(int $id): JsonResponse
    {
        $author = $this->authorRepository->find($id);

        if (! $author) {
            return response()->json(['message' => 'Author not found'], 404);
        }

        $author->book_count = $author->books->count();

        return response()->json($author);
    }

    public function update(UpdateAuthorRequest $request, int $id): JsonResponse
    {
        $author = $this->authorRepository->update($id, $request->validated());

        if (! $author) {
            return response()->json(['message' => 'Author not found'], 404);
        }

        return response()->json($author);
    }

    public function destroy(int $id): JsonResponse
    {
        $result = $this->authorRepository->delete($id);

        if (! $result) {
            return response()->json(['message' => 'Author not found'], 404);
        }

        return response()->json(['message' => 'Author deleted successfully']);
    }
}
