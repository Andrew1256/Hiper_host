<?php

namespace App\Http\Controllers\PublicArea;

use App\Http\Controllers\Controller;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(
        protected PostService $postService
    ) {
    }

    /**
     * Display the homepage — paginated published posts with sorting & author filter.
     */
    public function index(Request $request): View
    {
        $posts = $this->postService->getPublishedPosts(
            sort: $request->query('sort', 'newest'),
            authorId: $request->query('author') ? (int) $request->query('author') : null,
            perPage: 10
        );

        $topCommented = $this->postService->getTopCommentedPosts();
        $authors = $this->postService->getAuthorsWithPublishedPosts();

        return view('public.index', compact('posts', 'topCommented', 'authors'));
    }

    /**
     * Display a single post with comments.
     * Increments view counter (1 per IP per 60 seconds).
     */
    public function show(Request $request, string $slug): View
    {
        $post = $this->postService->getPostBySlug($slug);

        // Increment views (rate-limited by IP)
        $this->postService->incrementViews($post, $request->ip());

        $comments = $this->postService->getPostComments($post, perPage: 15);
        $topCommented = $this->postService->getTopCommentedPosts();

        return view('public.show', compact('post', 'comments', 'topCommented'));
    }
}
