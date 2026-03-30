<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(
        protected PostRepositoryInterface $postRepo
    ) {
    }

    /**
     * GET /api/posts
     */
    public function index(Request $request): PostCollection
    {
        $posts = $this->postRepo->getPublished(
            sort: $request->query('sort', 'newest'),
            authorId: $request->query('author') ? (int) $request->query('author') : null,
        );

        return new PostCollection($posts);
    }

    /**
     * GET /api/posts/{slug}
     */
    public function show(string $slug): PostResource
    {
        $post = $this->postRepo->findPublishedBySlug($slug);

        return new PostResource($post);
    }
}
