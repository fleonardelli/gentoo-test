<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function should_return_all_posts(): void
    {
        // Arrange
        Post::factory()->count(2)->create();

        // Act
        $response = $this->getJson('/posts');

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'result' => [
                '*' => [
                    'id',
                    'topic',
                    'created_at',
                    'updated_at',
                ]
            ],
            'count'
        ]);

        $responseData = $response->json();
        $this->assertEquals(2, $responseData['count']);
        $this->assertCount(2, $responseData['result']);
    }

    /** @test */
    public function should_return_filtered_posts_by_topic(): void
    {
        // Arrange
        Post::factory()->create(['topic' => 'Specific Topic']);
        Post::factory()->create(['topic' => 'Another Topic']);

        // Act
        $response = $this->getJson('/posts?topic=Specific');

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'result' => [
                '*' => [
                    'id',
                    'topic',
                    'created_at',
                    'updated_at',
                ]
            ],
            'count'
        ]);

        $responseData = $response->json();
        $this->assertEquals(1, $responseData['count']);
        $this->assertCount(1, $responseData['result']);
        $this->assertEquals('Specific Topic', $responseData['result'][0]['topic']);
    }

    /** @test */
    public function should_return_sorted_posts_by_id_descending(): void
    {
        // Arrange
        $posts = Post::factory()->count(2)->create();
        $sortedPosts = $posts->sortByDesc('id')->values();

        // Act
        $response = $this->getJson('/posts?sort=id&direction=desc');

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'result' => [
                '*' => [
                    'id',
                    'topic',
                    'created_at',
                    'updated_at',
                ]
            ],
            'count'
        ]);

        $responseData = $response->json();
        $this->assertEquals(2, $responseData['count']);
        $this->assertCount(2, $responseData['result']);

        foreach ($sortedPosts as $index => $post) {
            $this->assertEquals($post->id, $responseData['result'][$index]['id']);
        }
    }

    /** @test */
    public function should_return_paginated_posts(): void
    {
        // Arrange
        Post::factory()->count(3)->create();

        // Act
        $response = $this->getJson('/posts?limit=1&page=1');

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'result' => [
                '*' => [
                    'id',
                    'topic',
                    'created_at',
                    'updated_at',
                ]
            ],
            'count'
        ]);

        $responseData = $response->json();
        $this->assertEquals(3, $responseData['count']);
        $this->assertCount(1, $responseData['result']);
    }

    /** @test */
    public function should_return_posts_with_comments(): void
    {
        // Arrange
        // I would create a post and use ->has() to create child Comments. But it is failing.
        // tried also from the child to the parent, and setting post_id manually, but it is the same.
        // This works, it is not the best IMO, but at least shows what I want to test.
        $comment = Comment::factory()->create();
        // Act
        $response = $this->getJson('/posts?with=comments');

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'result' => [
                '*' => [
                    'id',
                    'topic',
                    'created_at',
                    'updated_at',
                    'comments' => [
                        '*' => [
                            'id',
                            'post_id',
                            'content',
                            'created_at',
                            'updated_at',
                        ]
                    ]
                ]
            ],
            'count'
        ]);

        $responseData = $response->json();

        $this->assertEquals(1, $responseData['count']);
        $this->assertCount(1, $responseData['result']);
        $this->assertEquals(1, count($responseData['result'][0]['comments']));
    }
}
