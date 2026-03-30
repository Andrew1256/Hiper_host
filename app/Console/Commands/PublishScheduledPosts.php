<?php

namespace App\Console\Commands;

use App\Services\PostPublisherService;
use Illuminate\Console\Command;

class PublishScheduledPosts extends Command
{
    protected $signature = 'posts:publish-scheduled';

    protected $description = 'Publish all scheduled posts where publish_at <= now';

    /**
     * Delegates to PostPublisherService (SRP).
     */
    public function handle(PostPublisherService $publisher): int
    {
        $count = $publisher->publishScheduled();

        $this->info(
            $count > 0
            ? "Successfully published {$count} scheduled post(s)."
            : 'No scheduled posts to publish.'
        );

        return \Symfony\Component\Console\Command\Command::SUCCESS;
    }
}
