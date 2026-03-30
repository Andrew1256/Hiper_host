<?php

namespace App\Http\Controllers\Api;

use App\DTOs\CommentData;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    public function __construct(
        protected PostRepositoryInterface $postRepo,
        protected CommentService $commentService
    ) {
    }

    /**
     * POST /api/v1/posts/{slug}/comments
     */
    public function store(StoreCommentRequest $request, string $slug): JsonResponse
    {
        // Uses repository — consistent with the rest of the app
        $post = $this->postRepo->findPublishedBySlug($slug);

        $comment = $this->commentService->createComment(
            $post,
            CommentData::fromRequest($request)
        );

        return (new CommentResource($comment))
            ->response()
            ->setStatusCode(201);
    }
}
