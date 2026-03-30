<?php

namespace App\Http\Controllers\PublicArea;

use App\DTOs\CommentData;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Post;
use App\Services\CommentService;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    public function __construct(
        protected CommentService $commentService
    ) {
    }

    /**
     * Store a new comment on a published post.
     */
    public function store(StoreCommentRequest $request, Post $post): RedirectResponse
    {
        $this->commentService->createComment(
            $post,
            CommentData::fromRequest($request)
        );

        return redirect()
            ->route('posts.show', $post->slug)
            ->with('success', 'Comment added successfully!');
    }
}
