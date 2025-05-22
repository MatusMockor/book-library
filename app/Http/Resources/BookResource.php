<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author_id' => $this->author_id,
            'author' => $this->whenLoaded('author', function () {
                return [
                    'id' => $this->author->id,
                    'name' => $this->author->name,
                ];
            }),
            'is_borrowed' => $this->is_borrowed,
            'borrowed_by' => $this->borrowed_by,
            'borrower' => $this->whenLoaded('borrower', function () {
                return [
                    'id' => $this->borrower->id,
                    'name' => $this->borrower->name,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
