<?php

namespace App\Http\Controllers\Frontend;

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
    ) {}

    public function store(StoreCommentRequest $request, Post $post): RedirectResponse
    {
        $this->commentService->createComment($post, CommentData::fromRequest($request));

        return redirect()
            ->route('posts.show', $post->slug)
            ->with('success', 'Your comment has been added!');
    }
}
