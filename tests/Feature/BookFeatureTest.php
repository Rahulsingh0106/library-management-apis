<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookFeatureTest extends TestCase
{
    public function test_admin_can_create_book()
    {
        $this->actingAsAdmin();

        $response = $this->postJson('/api/books', [
            'title' => 'Clean Code',
            'author' => 'Robert C. Martin',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['title' => 'Clean Code']);
    }

    public function test_user_cannot_create_book()
    {
        $this->actingAsUser();

        $response = $this->postJson('/api/books', [
            'title' => 'You Shouldn’t Be Here',
            'author' => 'Oops',
        ]);

        $response->assertStatus(403); // Forbidden
    }

    public function test_any_authenticated_user_can_view_books()
    {
        $this->actingAsUser();

        $response = $this->getJson('/api/books');
        $response->assertStatus(200);
    }
}
