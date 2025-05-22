<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = null;

    /**
     * Create a new anonymous resource collection.
     *
     * @param  mixed  $resource
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public static function collection($resource)
    {
        $collection = parent::collection($resource);
        $collection->preserveKeys = true;
        $collection->collects = __CLASS__;
        $collection->collection = $collection->collection->values();

        return $collection;
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'book_count' => $this->when(isset($this->book_count), $this->book_count),
            'books' => $this->whenLoaded('books', function () {
                return $this->books->map(function ($book) {
                    return [
                        'id' => $book->id,
                        'title' => $book->title,
                    ];
                });
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
