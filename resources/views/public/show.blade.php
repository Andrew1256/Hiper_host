@extends('layouts.app')

@section('title', $post->title . ' — HiperBlog')

@section('content')
    <div class="row g-5">
        <div class="col-lg-8">
            <article class="bg-white rounded-4 p-4 p-md-5 shadow-sm">
                @if($post->image)
                    <img src="{{ $post->image_url }}" class="img-fluid rounded-4 mb-5 w-100 object-fit-cover"
                        style="max-height: 480px;" alt="{{ $post->title }}">
                @endif

                <div class="mb-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold"
                            style="width: 48px; height: 48px; font-size: 1.2rem;">
                            {{ substr($post->user->name, 0, 1) }}
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">{{ $post->user->name }}</h6>
                            <small class="text-muted">Published on {{ $post->published_at?->format('F d, Y') }} •
                                {{ $post->views_count }} views</small>
                        </div>
                    </div>
                    <h1 class="display-5 fw-bold mb-4">{{ $post->title }}</h1>
                </div>

                <div class="post-content lh-lg text-dark fs-5 mb-5" style="white-space: pre-line;">
                    {{ $post->body }}
                </div>

                <hr class="my-5 border-light">

                <section id="comments">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                    <h3 class="fw-bold mb-0">{{ $post->comments_count }} Comments</h3>
                    @if($post->comments_count > 0)
                        @if($showingAll)
                            <a href="{{ request()->fullUrlWithQuery(['all_comments' => null]) }}#comments" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                <i class="bi bi-list-ul me-1"></i> Back to Pages
                            </a>
                        @else
                            <a href="{{ request()->fullUrlWithQuery(['all_comments' => 1]) }}#comments" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                <i class="bi bi-eye me-1"></i> View All
                            </a>
                        @endif
                    @endif
                </div>

                    <div class="bg-light rounded-4 p-4 mb-5">
                        <h5 class="fw-bold mb-4">Join the conversation</h5>
                        <form action="{{ route('comments.store', $post->slug) }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold small">Display Name</label>
                                    <input type="text" name="name"
                                        class="form-control rounded-3 py-2 @error('name') is-invalid @enderror"
                                        placeholder="Your name" value="{{ old('name') }}" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold small">Comment</label>
                                    <textarea name="body" class="form-control rounded-3 @error('body') is-invalid @enderror"
                                        rows="4" placeholder="What are your thoughts?" required>{{ old('body') }}</textarea>
                                    @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <div class="form-text text-end small">max 300 characters</div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary px-4 py-2">Post Comment</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="comment-list">
                        @forelse($comments as $comment)
                            <div class="d-flex gap-3 mb-4 last-mb-0">
                                <div class="avatar bg-soft-primary text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold shrink-0"
                                    style="width: 40px; height: 40px; background-color: #e0e7ff;">
                                    {{ substr($comment->name, 0, 1) }}
                                </div>
                                <div class="bg-white border rounded-4 p-3 flex-grow-1 shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="fw-bold mb-0">{{ $comment->name }}</h6>
                                        <small class="text-muted"
                                            style="font-size: 0.75rem;">{{ $comment->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-0 text-muted">{{ $comment->body }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 bg-light rounded-4">
                                <p class="text-muted mb-0">No comments yet. Be the first to share your thoughts!</p>
                            </div>
                        @endforelse

                        @if(! $showingAll)
                            <div class="mt-4">
                                {{ $comments->links() }}
                            </div>
                        @endif
                    </div>
                </section>
            </article>
        </div>

        <div class="col-lg-4">
            <aside class="sticky-top" style="top: 100px;">
                <div class="sidebar-card">
                    <h4 class="fw-bold mb-4">Trending Articles</h4>
                    <div class="list-group list-group-flush">
                        @foreach($topCommented as $top)
                            <a href="{{ route('posts.show', $top->slug) }}"
                                class="list-group-item list-group-item-action border-0 px-0 py-3 d-flex align-items-start gap-3 {{ $top->id === $post->id ? 'opacity-50 pointer-events-none' : '' }}">
                                <div class="h3 fw-bold text-light mb-0" style="opacity: 0.5;">
                                    {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</div>
                                <div>
                                    <h6 class="fw-bold mb-1 line-clamp-2 text-dark">{{ $top->title }}
                                        @if($top->id === $post->id) (Current) @endif</h6>
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
            </aside>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .last-mb-0:last-child {
            margin-bottom: 0;
        }
    </style>
@endsection