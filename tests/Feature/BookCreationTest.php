<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_book_is_created_in_database_with_valid_data(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $bookData = [
            'title' => '1984',
            'author' => 'George Orwell',
            'summary' => 'Roman dystopique dans une société totalitaire.',
            'isbn' => '9782070368228',
        ];

        $response = $this->postJson('/api/v1/books', $bookData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('books', [
            'title' => '1984',
            'author' => 'George Orwell',
            'summary' => 'Roman dystopique dans une société totalitaire.',
            'isbn' => '9782070368228',
        ]);
    }

    public function test_book_is_not_created_in_database_with_invalid_data(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $bookData = [
            'title' => 'AB',
            'author' => 'George Orwell',
            'summary' => 'Roman dystopique dans une société totalitaire.',
            'isbn' => '9782070368228',
        ];

        $response = $this->postJson('/api/v1/books', $bookData);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('books', [
            'title' => $bookData['title'],
        ]);
    }

    public function test_book_is_not_created_in_database_when_user_is_not_authenticated(): void
    {
        $bookData = [
            'title' => '1984',
            'author' => 'George Orwell',
            'summary' => 'Roman dystopique dans une société totalitaire.',
            'isbn' => '9782070368228',
        ];

        $response = $this->postJson('/api/v1/books', $bookData);

        $response->assertStatus(401);

        $this->assertDatabaseMissing('books', [
            'title' => $bookData['title'],
        ]);
    }
}
