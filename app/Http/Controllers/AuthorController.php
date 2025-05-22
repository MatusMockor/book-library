<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use App\Repositories\Interfaces\AuthorRepository;
use Illuminate\Http\JsonResponse;

class AuthorController extends Controller
{
    public function __construct(protected AuthorRepository $authorRepository)
    {
        $this->authorizeResource(Author::class);
    }

    public function index(): JsonResponse
    {
        $authors = $this->authorRepository->all();

        // Add book count to each author
        $authors->each(function ($author) {
            $author->book_count = $author->books->count();
        });

        return response()->json(AuthorResource::collection($authors)->resolve());
    }

    public function store(CreateAuthorRequest $request): JsonResponse
    {
        $author = $this->authorRepository->create($request->validated());

        return (new AuthorResource($author))->response()->setStatusCode(201);
    }

    public function show(Author $author): JsonResponse
    {
        $author->book_count = $author->books->count();

        return (new AuthorResource($author))->response();
    }

    public function update(UpdateAuthorRequest $request, Author $author): JsonResponse
    {
        $updatedAuthor = $this->authorRepository->update($author, $request->validated());

        return (new AuthorResource($updatedAuthor))->response();
    }

    public function destroy(Author $author): JsonResponse
    {
        $this->authorRepository->delete($author);

        return response()->json(['message' => 'Author deleted successfully']);
    }
}
