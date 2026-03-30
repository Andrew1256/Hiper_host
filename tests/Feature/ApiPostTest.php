<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiPostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_paginated_published_posts()
    {
        Post::factory()->count(15)->published()->create();
        Post::factory()->count(5)->draft()->create();

        $response = $this->getJson('/api/posts');

        $response->assertOk()
            ->assertJsonCount(10, 'data') // Page size is 10
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'slug', 'body', 'author', 'published_at', 'comments_count', 'views_count']
                ],
                'links',
                'meta'
            ]);
    }

    /** @test */
    public function it_returns_a_single_published_post_by_slug()
    {
        $post = Post::factory()->published()->create();

        $response = $this->getJson("/api/posts/{$post->slug}");

        $response->assertOk()
            ->assertJsonPath('data.title', $post->title);
    }

    /** @test */
    public function it_returns_404_for_draft_posts()
    {
        $post = Post::factory()->draft()->create();

        $response = $this->getJson("/api/posts/{$post->slug}");

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_post_a_comment_via_api()
    {
        $post = Post::factory()->published()->create();

        $response = $this->postJson("/api/posts/{$post->slug}/comments", [
            'name' => 'API User',
            'body' => 'Comment via API'
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'API User');

        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'name' => 'API User'
        ]);
    }
}
