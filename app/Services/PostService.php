<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Services\PostAnalyticsService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * PostService — orchestration layer over PostRepositoryInterface.
 *
 * Architectural note:
 *   The active Frontend\ controllers inject PostRepositoryInterface directly
 *   (with caching where needed). This service is retained for reference and
 *   future refactoring; it is NOT used by any active route at present.
 *
 *   All direct Eloquent calls have been removed; the service now delegates
 *   exclusively to the repository — resolving the dual-architecture issue.
 */
class PostService
{
    public function __construct(
        protected PostRepositoryInterface $postRepo,
        protected PostAnalyticsService $analyticsService
    ) {}

    /**
     * Get paginated published posts.
     */
    public function getPublishedPosts(
        ?string $sort = 'newest',
        ?int $authorId = null,
        int $perPage = 10
    ): LengthAwarePaginator {
        return $this->postRepo->getPublished($sort, $authorId, $perPage);
    }

    /**
     * Get a single published post by slug.
     */
    public function getPostBySlug(string $slug): Post
    {
        return $this->postRepo->findPublishedBySlug($slug);
    }

    /**
     * Get paginated comments for a post.
     */
    public function getPostComments(Post $post, int $perPage = 15): LengthAwarePaginator
    {
        return $this->postRepo->getComments($post, $perPage);
    }

    /**
     * Increment view counter — delegates to PostAnalyticsService.
     */
    public function incrementViews(Post $post, string $ip): void
    {
        $this->analyticsService->trackView($post, $ip);
    }

    /**
     * Get top-5 most commented posts (cached 5 min).
     */
    public function getTopCommentedPosts(int $limit = 5): Collection
    {
        return Cache::remember('top_commented_posts', 300, function () use ($limit) {
            return $this->postRepo->getTopCommented($limit);
        });
    }

    /**
     * Get all authors who have at least one published post.
     */
    public function getAuthorsWithPublishedPosts(): Collection
    {
        return $this->postRepo->getAuthorsWithPublished();
    }
}
