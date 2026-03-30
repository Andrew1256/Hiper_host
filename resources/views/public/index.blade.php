@extends('layouts.app')

@section('title', 'HiperBlog — Discover Amazing Stories')

@section('content')
    <div class="row g-5">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="fw-bold h2 mb-0">Latest Stories</h1>

                <div class="dropdown">
                    <button class="btn btn-white dropdown-toggle border rounded-3 fw-semibold" type="button"
                        data-bs-toggle="dropdown">
                        Sort: {{ ucfirst(str_replace('_', ' ', request('sort', 'newest'))) }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li><a class="dropdown-item {{ request('sort') == 'newest' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}">Newest</a></li>
                        <li><a class="dropdown-item {{ request('sort') == 'most_commented' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['sort' => 'most_commented']) }}">Most Commented</a>
                        </li>
                        <li><a class="dropdown-item {{ request('sort') == 'most_viewed' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['sort' => 'most_viewed']) }}">Most Viewed</a></li>
                    </ul>
                </div>
            </div>

            @if(request('author'))
                <div
                    class="alert alert-info border-0 shadow-sm rounded-4 mb-4 d-flex justify-content-between align-items-center">
                    <span>Filtering by author:
                        <strong>{{ $authors->firstWhere('id', request('author'))->name ?? 'Unknown' }}</strong></span>
                    <a href="{{ route('home') }}" class="btn btn-sm btn-light rounded-3">Clear Filter</a>
                </div>
            @endif

            <div class="row g-4">
                @forelse($posts as $post)
                    <div class="col-12">
                        <article class="card h-100 overflow-hidden">
                            <div class="row g-0">
                                @if($post->image)
                                    <div class="col-md-4">
                                        <img src="{{ $post->image_url }}" class="img-fluid h-100 object-fit-cover"
                                            alt="{{ $post->title }}">
                                    </div>
                                @endif
                                <div class="col-md-{{ $post->image ? '8' : '12' }}">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <span
                                                class="badge bg-light text-primary rounded-pill px-3 py-2 fw-semibold">Article</span>
                                            <small class="text-muted">{{ $post->published_at?->format('M d, Y') }}</small>
                                        </div>
                                        <h2 class="card-title h4 fw-bold mb-3 mt-1">
                                            <a href="{{ route('posts.show', $post->slug) }}"
                                                class="text-decoration-none text-dark hover-primary">{{ $post->title }}</a>
                                        </h2>
                                        <p class="card-text text-muted mb-4">{{ Str::limit(strip_tags($post->body), 160) }}</p>
                                        <div class="d-flex align-items-center justify-content-between mt-auto">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                                    style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                    {{ substr($post->user->name, 0, 1) }}
                                                </div>
                                                <a href="{{ route('home', ['author' => $post->user_id]) }}"
                                                    class="text-decoration-none text-dark small fw-bold">{{ $post->user->name }}</a>
                                            </div>
                                            <div class="text-muted small">
                                                <span class="me-3"><i class="bi bi-chat-text me-1"></i>
                                                    {{ $post->comments_count }} comments</span>
                                                <span><i class="bi bi-eye me-1"></i> {{ $post->views_count }} views</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <img src="https://illustrations.popsy.co/blue/crashed-error.svg" alt="No posts"
                            style="max-height: 200px;" class="mb-4">
                        <h3 class="fw-bold">No articles found</h3>
                        <p class="text-muted">We couldn't find any articles matching your criteria.</p>
                    </div>
                @endforelse

                <div class="mt-5 d-flex justify-content-center">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <aside class="sticky-top" style="top: 100px;">
                <div class="sidebar-card">
                    <h4 class="fw-bold mb-4">Trending Articles</h4>
                    <div class="list-group list-group-flush">
                        @foreach($topCommented as $top)
                            <a href="{{ route('posts.show', $top->slug) }}"
                                class="list-group-item list-group-item-action border-0 px-0 py-3 d-flex align-items-start gap-3">
                                <div class="h3 fw-bold text-light mb-0" style="opacity: 0.5;">
                                    {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 line-clamp-2 text-dark">{{ $top->title }}</h6>
                                    <div class="small text-muted d-flex align-items-center gap-2">
                                        <span>{{ $top->user->name ?? $top->author_name }}</span>
                                        <span>•</span>
                                        <span>{{ $top->comments_count }} comments</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="sidebar-card">
                    <h4 class="fw-bold mb-4">Authors</h4>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($authors as $author)
                            <a href="{{ route('home', ['author' => $author->id]) }}"
                                class="btn btn-light rounded-pill px-3 fw-medium {{ request('author') == $author->id ? 'active btn-primary' : '' }}">
                                {{ $author->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .hover-primary:hover {
            color: var(--primary-color);
        }

        .list-group-item-action:hover {
            background: transparent;
        }
    </style>
@endsection