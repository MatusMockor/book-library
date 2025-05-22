<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_book(): void
    {
        $admin = User::factory()->admin()->create();
        $author = \App\Models\Author::factory()->create();

        $response = $this
            ->actingAs($admin)
            ->post(route('api.books.store'), [
                'title' => 'Test Book',
                'author_id' => $author->id,
                'is_borrowed' => false,
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('books', ['title' => 'Test Book']);
    }

    public function test_admin_can_update_book(): void
    {
        $admin = User::factory()->admin()->create();
        $book = Book::factory()->create(['title' => 'Original Title']);

        $response = $this
            ->actingAs($admin)
            ->patch(route('api.books.update', $book), [
                'title' => 'Updated Title',
                'author_id' => $book->author_id,
                'is_borrowed' => $book->is_borrowed,
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('books', ['id' => $book->id, 'title' => 'Updated Title']);
    }

    public function test_admin_can_delete_book(): void
    {
        $admin = User::factory()->admin()->create();
        $book = Book::factory()->create();

        $response = $this
            ->actingAs($admin)
            ->delete(route('api.books.destroy', $book));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    public function test_regular_user_cannot_create_book(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $author = \App\Models\Author::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('api.books.store'), [
                'title' => 'Test Book',
                'author_id' => $author->id,
                'is_borrowed' => false,
            ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('books', ['title' => 'Test Book']);
    }

    public function test_regular_user_cannot_update_book(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $book = Book::factory()->create(['title' => 'Original Title']);

        $response = $this
            ->actingAs($user)
            ->patch(route('api.books.update', $book), [
                'title' => 'Updated Title',
                'author_id' => $book->author_id,
                'is_borrowed' => $book->is_borrowed,
            ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('books', ['id' => $book->id, 'title' => 'Original Title']);
    }

    public function test_regular_user_cannot_delete_book(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $book = Book::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete(route('api.books.destroy', $book));

        $response->assertStatus(403);
        $this->assertDatabaseHas('books', ['id' => $book->id]);
    }

    public function test_admin_can_toggle_borrowed_status(): void
    {
        $admin = User::factory()->admin()->create();
        $book = Book::factory()->create(['is_borrowed' => false]);

        $response = $this
            ->actingAs($admin)
            ->patch(route('api.books.toggle-borrowed', $book));

        $response->assertStatus(200);
        $this->assertDatabaseHas('books', ['id' => $book->id, 'is_borrowed' => true]);
    }

    public function test_regular_user_can_toggle_borrowed_status(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $book = Book::factory()->create(['is_borrowed' => false]);

        $response = $this
            ->actingAs($user)
            ->patch(route('api.books.toggle-borrowed', $book));

        $response->assertStatus(200);
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'is_borrowed' => true,
            'borrowed_by' => $user->id,
        ]);
    }
}
