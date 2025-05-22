<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_all_authors(): void
    {
        // Create a regular user
        $user = User::factory()->create(['is_admin' => false]);

        // Create some test authors
        $authors = Author::factory()->count(3)->create();

        // Make the request as an authenticated user
        $response = $this
            ->actingAs($user)
            ->get(route('api.authors.index'));

        // Assert response is successful and contains the authors
        $response->assertStatus(200);
        $response->assertJsonCount(3);

        // Check that each author has a book_count attribute
        $responseData = $response->json();
        foreach ($responseData as $author) {
            $this->assertArrayHasKey('book_count', $author);
        }
    }

    public function test_admin_can_create_author(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this
            ->actingAs($admin)
            ->post(route('authors.store'), [
                'name' => 'John',
                'surname' => 'Doe',
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('authors', [
            'name' => 'John',
            'surname' => 'Doe',
        ]);
    }

    public function test_admin_can_view_author(): void
    {
        $admin = User::factory()->admin()->create();
        $author = Author::factory()->create();

        $response = $this
            ->actingAs($admin)
            ->get(route('authors.show', $author));

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $author->id,
            'name' => $author->name,
            'surname' => $author->surname,
        ]);

        // Check that the author has a book_count attribute
        $responseData = $response->json();
        $this->assertArrayHasKey('book_count', $responseData);
    }

    public function test_admin_can_update_author(): void
    {
        $admin = User::factory()->admin()->create();
        $author = Author::factory()->create(['name' => 'Original', 'surname' => 'Name']);

        $response = $this
            ->actingAs($admin)
            ->put(route('authors.update', $author), [
                'name' => 'Updated',
                'surname' => 'Author',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('authors', [
            'id' => $author->id,
            'name' => 'Updated',
            'surname' => 'Author',
        ]);
    }

    public function test_admin_can_delete_author(): void
    {
        $admin = User::factory()->admin()->create();
        $author = Author::factory()->create();

        $response = $this
            ->actingAs($admin)
            ->delete(route('authors.destroy', $author));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('authors', ['id' => $author->id]);
    }

    public function test_regular_user_cannot_create_author(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this
            ->actingAs($user)
            ->post(route('authors.store'), [
                'name' => 'John',
                'surname' => 'Doe',
            ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('authors', [
            'name' => 'John',
            'surname' => 'Doe',
        ]);
    }

    public function test_regular_user_cannot_update_author(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $author = Author::factory()->create(['name' => 'Original', 'surname' => 'Name']);

        $response = $this
            ->actingAs($user)
            ->put(route('authors.update', $author), [
                'name' => 'Updated',
                'surname' => 'Author',
            ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('authors', [
            'id' => $author->id,
            'name' => 'Original',
            'surname' => 'Name',
        ]);
    }

    public function test_regular_user_cannot_delete_author(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $author = Author::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete(route('authors.destroy', $author));

        $response->assertStatus(403);
        $this->assertDatabaseHas('authors', ['id' => $author->id]);
    }

    public function test_guest_access_to_authors_is_forbidden(): void
    {
        // Create some test authors
        $authors = Author::factory()->count(3)->create();

        // Make the request as a guest (not logged in)
        $response = $this->get(route('api.authors.index'));

        // Assert response is forbidden (403) or unauthorized (401)
        $response->assertStatus(403);
    }

    public function test_guest_can_view_author_details(): void
    {
        $author = Author::factory()->create();

        // Make the request as a guest (not logged in)
        $response = $this->get(route('authors.show', $author));

        // Assert response is successful (200) - this is the actual behavior
        $response->assertStatus(200);
    }
}
