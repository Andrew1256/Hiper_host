<?php

namespace App\Repositories\Interfaces;

use App\Models\Comment;
use App\Models\Post;

interface CommentRepositoryInterface
{
    /**
     * Create a new comment for a post.
     *
     * @param Post $post
     * @param array $data
     * @return Comment
     */
    public function createForPost(Post $post, array $data): Comment;

    /**
     * Delete a comment.
     *
     * @param Comment $comment
     * @return bool
     */
    public function delete(Comment $comment): bool;
}
