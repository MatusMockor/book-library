<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'title',
        'is_borrowed',
    ];

    protected $casts = [
        'is_borrowed' => 'boolean',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}
