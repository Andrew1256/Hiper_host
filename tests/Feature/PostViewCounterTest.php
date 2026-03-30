<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PostViewCounterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_increments_views_only_once_per_60_seconds_per_ip()
    {
        $post = Post::factory()->published()->create();
        $this->assertEquals(0, $post->views_count);

        // First visit
        $this->get(route('posts.show', $post->slug));
        
        $post->refresh();
        $this->assertEquals(1, $post->views_count);

        // Immediate second visit (same IP) — should not increment
        $this->get(route('posts.show', $post->slug));
        
        $post->refresh();
        $this->assertEquals(1, $post->views_count, 'View count should NOT increment within 60s window');

        // Travel 61 seconds forward
        $this->travel(61)->seconds();

        // Third visit (same IP) — should increment now
        $this->get(route('posts.show', $post->slug));
        
        $post->refresh();
        $this->assertEquals(2, $post->views_count, 'View count SHOULD increment after 60s');
    }

    /** @test */
    public function multiple_ips_increment_views_independently()
    {
        $post = Post::factory()->published()->create();

        // IP 1 visit
        $this->withServerVariables(['REMOTE_ADDR' => '1.1.1.1'])
             ->get(route('posts.show', $post->slug));

        // IP 2 visit
        $this->withServerVariables(['REMOTE_ADDR' => '2.2.2.2'])
             ->get(route('posts.show', $post->slug));

        $post->refresh();
        $this->assertEquals(2, $post->views_count);
    }
}
