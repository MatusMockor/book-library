<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\User;
use App\Repositories\BookRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private BookRepository $bookRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bookRepository = new BookRepository;
    }

    public function test_all_returns_all_books_with_author_and_borrower(): void
    {
        $books = Book::factory()->count(3)->create();

        $result = $this->bookRepository->all();

        $this->assertCount(3, $result);
        $this->assertTrue($result->first()->relationLoaded('author'));
        $this->assertTrue($result->first()->relationLoaded('borrower'));
    }

    public function test_find_returns_book_with_author_and_borrower(): void
    {
        $book = Book::factory()->create();

        $result = $this->bookRepository->find($book->id);

        $this->assertEquals($book->id, $result->id);
        $this->assertTrue($result->relationLoaded('author'));
        $this->assertTrue($result->relationLoaded('borrower'));
    }

    public function test_toggle_book_borrowed_status_from_available_to_borrowed(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['is_borrowed' => false, 'borrowed_by' => null]);

        $result = $this->bookRepository->toggleBookBorrowedStatus($book, $user->id);

        $this->assertTrue($result->is_borrowed);
        $this->assertEquals($user->id, $result->borrowed_by);
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'is_borrowed' => true,
            'borrowed_by' => $user->id,
        ]);
    }

    public function test_toggle_book_borrowed_status_from_borrowed_to_available(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->borrowedBy($user->id)->create();

        $result = $this->bookRepository->toggleBookBorrowedStatus($book, $user->id);

        $this->assertFalse($result->is_borrowed);
        $this->assertNull($result->borrowed_by);
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'is_borrowed' => false,
            'borrowed_by' => null,
        ]);
    }

    public function test_create_returns_created_book(): void
    {
        $author = \App\Models\Author::factory()->create();
        $data = [
            'title' => 'Test Book',
            'author_id' => $author->id,
            'is_borrowed' => false,
        ];

        $result = $this->bookRepository->create($data);

        $this->assertEquals('Test Book', $result->title);
        $this->assertEquals($author->id, $result->author_id);
        $this->assertFalse($result->is_borrowed);
        $this->assertDatabaseHas('books', [
            'title' => 'Test Book',
            'author_id' => $author->id,
            'is_borrowed' => false,
        ]);
    }

    public function test_update_book_returns_updated_book(): void
    {
        $book = Book::factory()->create();
        $title = fake()->title;
        $data = [
            'title' => $title,
            'author_id' => $book->author_id,
            'is_borrowed' => $book->is_borrowed,
        ];

        $result = $this->bookRepository->update($book, $data);

        $this->assertEquals($title, $result->title);
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => $title,
        ]);
    }

    public function test_delete_returns_true_for_existing_book(): void
    {
        $book = Book::factory()->create();

        $result = $this->bookRepository->delete($book);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    public function test_get_by_author_id_returns_books_by_author(): void
    {
        $author = \App\Models\Author::factory()->create();
        Book::factory()->count(2)->create(['author_id' => $author->id]);
        Book::factory()->create(); // Another book with different author

        $result = $this->bookRepository->getByAuthorId($author->id);

        $this->assertCount(2, $result);
        $this->assertEquals($author->id, $result->first()->author_id);
    }
}
