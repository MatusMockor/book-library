<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_all_books(): void
    {
        $user = User::factory()->create();
        $books = Book::factory()->count(3)->create();

        $response = $this->actingAs($user)->get(route('api.books.index'));

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    public function test_user_can_borrow_available_book(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $book = Book::factory()->create(['is_borrowed' => false]);

        $response = $this
            ->actingAs($user)
            ->patch(route('books.toggle-borrowed', $book));

        $response->assertStatus(200);
        $response->assertJson(['is_borrowed' => true]);
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'is_borrowed' => true,
            'borrowed_by' => $user->id
        ]);
    }

    public function test_borrower_can_return_book(): void
    {
        $borrower = User::factory()->create(['is_admin' => false]);
        $book = Book::factory()->borrowedBy($borrower->id)->create();

        $response = $this
            ->actingAs($borrower)
            ->patch(route('books.toggle-borrowed', $book));

        $response->assertStatus(200);
        $response->assertJson(['is_borrowed' => false]);
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'is_borrowed' => false,
            'borrowed_by' => null
        ]);
    }

    public function test_non_borrower_cannot_return_book(): void
    {
        $borrower = User::factory()->create(['is_admin' => false]);
        $otherUser = User::factory()->create(['is_admin' => false]);
        $book = Book::factory()->borrowedBy($borrower->id)->create();

        $response = $this
            ->actingAs($otherUser)
            ->patch(route('books.toggle-borrowed', $book));

        $response->assertStatus(403);
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'is_borrowed' => true,
            'borrowed_by' => $borrower->id
        ]);
    }

    public function test_admin_can_return_any_borrowed_book(): void
    {
        $borrower = User::factory()->create(['is_admin' => false]);
        $admin = User::factory()->admin()->create();
        $book = Book::factory()->borrowedBy($borrower->id)->create();

        $response = $this
            ->actingAs($admin)
            ->patch(route('books.toggle-borrowed', $book));

        $response->assertStatus(200);
        $response->assertJson(['is_borrowed' => false]);
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'is_borrowed' => false,
            'borrowed_by' => null
        ]);
    }

    public function test_borrowed_by_field_is_updated_correctly(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $book = Book::factory()->create(['is_borrowed' => false, 'borrowed_by' => null]);

        // Borrow the book
        $this->actingAs($user)
            ->patch(route('books.toggle-borrowed', $book));

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'is_borrowed' => true,
            'borrowed_by' => $user->id
        ]);

        // Return the book
        $this->actingAs($user)
            ->patch(route('books.toggle-borrowed', $book->fresh()));

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'is_borrowed' => false,
            'borrowed_by' => null
        ]);
    }
}
