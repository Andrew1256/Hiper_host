<?php

namespace App\Repositories\Interfaces;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface PostRepositoryInterface
{
    /**
     * Get paginated published posts with sorting and optional author filter.
     */
    public function getPublished(string $sort = 'newest', ?int $authorId = null, int $perPage = 10): LengthAwarePaginator;

    /**
     * Find a published post by slug or throw 404.
     */
    public function findPublishedBySlug(string $slug): Post;

    /**
     * Get the top-N most commented published posts (Eloquent).
     */
    public function getTopCommented(int $limit = 5): Collection;

    /**
     * Get the top-N most commented published posts (raw SQL for performance).
     */
    public function getTopCommentedRaw(int $limit = 5): Collection;

    /**
     * Create a new post from an array of attributes.
     */
    public function create(array $attributes): Post;

    /**
     * Update an existing post from an array of attributes.
     */
    public function update(Post $post, array $attributes): Post;

    /**
     * Delete a post.
     */
    public function delete(Post $post): bool;

    /**
     * Atomically increment the view counter.
     */
    public function incrementViews(int $postId): void;

    /**
     * Atomically increment the comment counter.
     */
    public function incrementComments(int $postId): void;

    /**
     * Publish all scheduled posts where publish_at <= now. Returns count.
     */
    public function publishScheduled(): int;

    /**
     * Get authors who have at least one published post.
     */
    public function getAuthorsWithPublished(): Collection;

    /**
     * Get paginated posts for admin. If userId is given, filter by that author.
     */
    public function getForAdmin(?int $userId = null, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get paginated or all comments for a post.
     * If perPage is null, returns all comments in one page (paginated with high limit).
     */
    public function getComments(Post $post, ?int $perPage = 15): LengthAwarePaginator;
}
