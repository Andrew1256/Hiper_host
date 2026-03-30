<?php

namespace App\Repositories\Eloquent;

use App\Models\Post;
use App\Models\User;
use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PostRepository implements PostRepositoryInterface
{
    public function getPublished(string $sort = 'newest', ?int $authorId = null, int $perPage = 10): LengthAwarePaginator
    {
        return Post::query()
            ->published()
            ->with('user:id,name')
            ->sorted($sort)
            ->byAuthor($authorId)
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findPublishedBySlug(string $slug): Post
    {
        return Post::query()
            ->published()
            ->with('user:id,name')
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function getTopCommented(int $limit = 5): Collection
    {
        return Post::query()
            ->published()
            ->with('user:id,name')
            ->orderByDesc('comments_count')
            ->limit($limit)
            ->get();
    }

    public function getTopCommentedRaw(int $limit = 5): Collection
    {
        $results = DB::select("
            SELECT p.id, p.title, p.slug, p.comments_count, p.views_count,
                   u.name AS author_name
            FROM posts p
            INNER JOIN users u ON p.user_id = u.id
            WHERE p.status = ?
            ORDER BY p.comments_count DESC
            LIMIT ?
        ", [Post::STATUS_PUBLISHED, $limit]);

        return collect($results);
    }

    public function create(array $attributes): Post
    {
        return Post::create($attributes);
    }

    public function update(Post $post, array $attributes): Post
    {
        $post->update($attributes);

        return $post->fresh();
    }

    public function delete(Post $post): bool
    {
        return $post->delete();
    }

    public function incrementViews(int $postId): void
    {
        Post::where('id', $postId)->increment('views_count');
    }

    public function incrementComments(int $postId): void
    {
        Post::where('id', $postId)->increment('comments_count');
    }

    public function publishScheduled(): int
    {
        return Post::scheduled()->update([
            'status'       => Post::STATUS_PUBLISHED,
            'published_at' => now(),
        ]);
    }

    public function getAuthorsWithPublished(): Collection
    {
        return User::whereHas('posts', fn($q) => $q->published())
            ->select('id', 'name')
            ->get();
    }

    public function getForAdmin(?int $userId = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = Post::with('user:id,name')->latest();

        if ($userId !== null) {
            $query->where('user_id', $userId);
        }

        return $query->paginate($perPage);
    }

    public function getComments(Post $post, ?int $perPage = 15): LengthAwarePaginator
    {
        $query = $post->comments()->orderByDesc('created_at');

        if ($perPage === null) {
            // Effectively show all by using a very large perPage limit
            // This maintains compatibility with paginator methods in Blade
            return $query->paginate(1000000);
        }

        return $query->paginate($perPage);
    }
}
