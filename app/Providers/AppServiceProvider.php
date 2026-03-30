<?php

namespace App\Providers;

use App\Models\Post;
use App\Policies\PostPolicy;
use App\Repositories\Eloquent\CommentRepository;
use App\Repositories\Eloquent\PostRepository;
use App\Repositories\Interfaces\CommentRepositoryInterface;
use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register all application services and repository bindings.
     */
    public function register(): void
    {
        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);
    }

    /**
     * Bootstrap application services.
     */
    public function boot(): void
    {
        // Use Bootstrap 5 for pagination
        Paginator::useBootstrapFive();

        // Policies — EventServiceProvider.$listen handles events; no duplicate registration here.
        Gate::policy(Post::class, PostPolicy::class);
    }
}
