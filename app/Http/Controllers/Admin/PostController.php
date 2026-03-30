<?php

namespace App\Http\Controllers\Admin;

use App\DTOs\PostData;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Services\PostActionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(
        protected PostRepositoryInterface $postRepo,
        protected PostActionService $postActionService
    ) {
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Post::class);

        $user = $request->user();
        $filterUserId = $user->isEditor() ? $user->id : null;

        $posts = $this->postRepo->getForAdmin($filterUserId);

        return view('admin.posts.index', compact('posts'));
    }

    public function create(): View
    {
        $this->authorize('create', Post::class);

        return view('admin.posts.create');
    }

    public function store(StorePostRequest $request): RedirectResponse
    {
        $this->authorize('create', Post::class);

        $post = $this->postActionService->createPost(
            PostData::fromRequest($request)
        );

        return redirect()
            ->route('admin.posts.index')
            ->with('success', "Post \"{$post->title}\" created successfully!");
    }

    public function edit(Post $post): View
    {
        $this->authorize('update', $post);

        return view('admin.posts.edit', compact('post'));
    }

    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        $this->authorize('update', $post);

        $this->postActionService->updatePost($post, PostData::fromRequest($request));

        return redirect()
            ->route('admin.posts.index')
            ->with('success', "Post \"{$post->title}\" updated successfully!");
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->authorize('delete', $post);

        $title = $post->title;
        $this->postActionService->deletePost($post);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', "Post \"{$title}\" deleted successfully!");
    }
}
