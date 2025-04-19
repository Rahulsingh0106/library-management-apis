<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BorrowingFeatureTest extends TestCase
{
    public function test_user_can_borrow_and_return_book()
    {
        $user = $this->actingAsUser();
        $book = Book::factory()->create();

        $borrow = $this->postJson("/api/borrow/{$book->id}");
        $borrow->assertStatus(200)->assertJson(['message' => 'Book borrowed successfully']);

        $this->assertDatabaseHas('borrowings', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);

        $return = $this->postJson("/api/return/{$book->id}");
        $return->assertStatus(200)->assertJson(['message' => 'Book returned successfully']);
    }
}
