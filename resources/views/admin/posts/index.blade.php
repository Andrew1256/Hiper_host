@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h1 class="fw-bold h2 mb-1">My Articles</h1>
        <p class="text-muted mb-0">Manage and oversee your blog content</p>
    </div>
    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary d-flex align-items-center gap-2 px-4 py-2 rounded-3 shadow-sm">
        <i class="bi bi-plus-lg"></i>
        <span>Create Post</span>
    </a>
</div>

<div class="admin-card overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="px-4 py-3 text-uppercase small fw-bold text-secondary">Title</th>
                    <th class="py-3 text-uppercase small fw-bold text-secondary">Status</th>
                    <th class="py-3 text-uppercase small fw-bold text-secondary">Stats</th>
                    <th class="py-3 text-uppercase small fw-bold text-secondary text-end px-4">Actions</th>
                </tr>
            </thead>
            <tbody class="border-top-0">
                @forelse($posts as $post)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="d-flex align-items-center gap-3">
                                @if($post->image)
                                    <img src="{{ $post->image_url }}" class="rounded-3 object-fit-cover" style="width: 48px; height: 48px;">
                                @else
                                    <div class="rounded-3 bg-light d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                        <i class="bi bi-card-image text-secondary"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">{{ $post->title }}</h6>
                                    <small class="text-muted">by {{ $post->user->name }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="py-3">
                            <span class="badge rounded-pill px-3 py-2 fw-semibold badge-{{ $post->status }}">
                                {{ ucfirst($post->status) }}
                            </span>
                            @if($post->status === 'scheduled' && $post->publish_at)
                                <div class="small text-muted mt-1" style="font-size: 0.7rem;">
                                    {{ $post->publish_at->format('M d, H:i') }}
                                </div>
                            @endif
                        </td>
                        <td class="py-3">
                            <div class="d-flex gap-3 text-muted small">
                                <span title="Comments"><i class="bi bi-chat-text me-1"></i> {{ $post->comments_count }}</span>
                                <span title="Views"><i class="bi bi-eye me-1"></i> {{ $post->views_count }}</span>
                            </div>
                        </td>
                        <td class="py-3 text-end px-4">
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm rounded-3 shadow-sm border" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                    <li><a class="dropdown-item px-3 py-2" href="{{ route('admin.posts.edit', $post->id) }}"><i class="bi bi-pencil me-2 text-primary"></i> Edit</a></li>
                                    @if($post->status === 'published')
                                        <li><a class="dropdown-item px-3 py-2" href="{{ route('posts.show', $post->slug) }}" target="_blank"><i class="bi bi-eye me-2 text-info"></i> View Live</a></li>
                                    @endif
                                    <li><hr class="dropdown-divider mx-3"></li>
                                    <li>
                                        <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Delete this article forever?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="dropdown-item px-3 py-2 text-danger"><i class="bi bi-trash me-2"></i> Delete</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <i class="bi bi-inbox display-4 text-light mb-3 d-block"></i>
                            <h5 class="fw-bold">No articles yet</h5>
                            <p class="text-muted">Start sharing your thoughts with the world</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-4 border-top">
        {{ $posts->links() }}
    </div>
</div>
@endsection
