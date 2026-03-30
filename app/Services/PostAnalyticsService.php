<?php

namespace App\Services;

use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class PostAnalyticsService
{
    public function __construct(
        protected PostRepositoryInterface $postRepo
    ) {}

    /**
     * Track a post view. Max 1 view per IP per 60 seconds.
     *
     * Uses Cache::add() which is atomic (single write if key absent).
     * This eliminates the race condition that existed with has() + put().
     */
    public function trackView(Post $post, string $ip): void
    {
        $cacheKey = "post_view_{$post->id}_{$ip}";

        // Cache::add() returns false if key already exists — atomically safe.
        if (! Cache::add($cacheKey, true, 60)) {
            return;
        }

        $this->postRepo->incrementViews($post->id);
    }
}
