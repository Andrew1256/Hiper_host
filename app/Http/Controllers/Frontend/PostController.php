<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Services\PostAnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(
        protected PostRepositoryInterface $postRepo,
        protected PostAnalyticsService $analyticsService
    ) {}

    /**
     * Homepage — paginated published posts.
     */
    public function index(Request $request): View
    {
        $posts = $this->postRepo->getPublished(
            sort: $request->query('sort', 'newest'),
            authorId: $request->query('author') ? (int) $request->query('author') : null,
        );

        $topCommented = $this->getCachedTopCommented();
        $authors = $this->postRepo->getAuthorsWithPublished();

        return view('public.index', compact('posts', 'topCommented', 'authors'));
    }

    /**
     * Single post page with comments.
     */
    public function show(Request $request, string $slug): View
    {
        $post = $this->postRepo->findPublishedBySlug($slug);

        $this->analyticsService->trackView($post, $request->ip());

        $showingAll = $request->query('all_comments') === '1';
        $comments = $this->postRepo->getComments($post, $showingAll ? null : 15);
        $topCommented = $this->getCachedTopCommented();

        return view('public.show', compact('post', 'comments', 'topCommented', 'showingAll'));
    }

    /**
     * Sidebar data: top-5 commented posts cached for 5 min.
     */
    private function getCachedTopCommented()
    {
        return Cache::remember('top_commented_posts', 300, function () {
            return $this->postRepo->getTopCommented();
        });
    }
}
