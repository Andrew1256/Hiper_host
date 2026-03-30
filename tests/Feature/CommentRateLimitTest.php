<?php

namespace Tests\Feature;

use App\Events\CommentCreated;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CommentRateLimitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_throttles_comments_to_one_every_30_seconds_per_ip()
    {
        Event::fake();
        $post = Post::factory()->published()->create();
        
        // First comment should be successful
        $response = $this->post(route('comments.store', $post->slug), [
            'name' => 'John Doe',
            'body' => 'First valid comment'
        ]);
        $response->assertRedirect();
        $this->assertDatabaseCount('comments', 1);

        // Immediate 2nd comment (same IP) — should fail
        $response = $this->post(route('comments.store', $post->slug), [
            'name' => 'John Doe',
            'body' => '2nd comment too soon'
        ]);
        
        $response->assertStatus(429); // Too Many Requests
        $this->assertDatabaseCount('comments', 1);

        // Travel 31 seconds forward
        $this->travel(31)->seconds();

        // 3rd comment (same IP) — should work now
        $response = $this->post(route('comments.store', $post->slug), [
            'name' => 'John Doe',
            'body' => 'Comment after 30s reset'
        ]);
        
        $response->assertRedirect();
        $this->assertDatabaseCount('comments', 2);
    }
}
