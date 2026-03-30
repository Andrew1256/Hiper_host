<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class PublishScheduledCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_publishes_scheduled_posts_whose_time_has_passed()
    {
        $duePost = Post::factory()->create([
            'status' => Post::STATUS_SCHEDULED,
            'publish_at' => now()->subMinute(),
        ]);

        $futurePost = Post::factory()->create([
            'status' => Post::STATUS_SCHEDULED,
            'publish_at' => now()->addHour(),
        ]);

        $this->artisan('posts:publish-scheduled')->assertExitCode(0);

        $duePost->refresh();
        $futurePost->refresh();

        $this->assertEquals(Post::STATUS_PUBLISHED, $duePost->status);
        $this->assertNotNull($duePost->published_at);

        $this->assertEquals(Post::STATUS_SCHEDULED, $futurePost->status);
        $this->assertNull($futurePost->published_at);
    }
}
