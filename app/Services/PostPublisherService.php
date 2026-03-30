<?php

namespace App\Services;

use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Support\Facades\Log;

class PostPublisherService
{
    public function __construct(
        protected PostRepositoryInterface $postRepo
    ) {}

    /**
     * Publish all scheduled posts where publish_at <= now.
     */
    public function publishScheduled(): int
    {
        $count = $this->postRepo->publishScheduled();

        Log::info("PostPublisherService: published {$count} scheduled post(s).");

        return $count;
    }
}
