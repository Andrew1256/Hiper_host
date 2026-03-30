<?php

namespace App\Repositories\Eloquent;

use App\Models\Comment;
use App\Models\Post;
use App\Repositories\Interfaces\CommentRepositoryInterface;

class CommentRepository implements CommentRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function createForPost(Post $post, array $data): Comment
    {
        return $post->comments()->create($data);
    }

    /**
     * @inheritDoc
     */
    public function delete(Comment $comment): bool
    {
        return $comment->delete();
    }
}
