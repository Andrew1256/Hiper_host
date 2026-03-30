<?php

namespace App\Listeners;

use App\Events\CommentCreated;
use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class IncrementCommentCount
{
    public function __construct(
        protected PostRepositoryInterface $postRepo
    ) {}

    /**
     * Atomically increment comments_count via Repository.
     * Clears BOTH sidebar cache keys (Eloquent + Raw variants).
     */
    public function handle(CommentCreated $event): void
    {
        $this->postRepo->incrementComments($event->comment->post_id);

        Cache::forget('top_commented_posts');
        Cache::forget('top_commented_posts_raw');
    }
}
