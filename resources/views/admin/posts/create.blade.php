@extends('layouts.admin')

@section('content')
<div class="mb-5">
    <a href="{{ route('admin.posts.index') }}" class="text-decoration-none text-muted small fw-bold mb-2 d-inline-block">← Back to List</a>
    <h1 class="fw-bold h2">Write New Article</h1>
</div>

<form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="admin-card p-4 p-md-5 mb-4">
                <div class="mb-4">
                    <label class="form-label fw-bold">Title</label>
                    <input type="text" name="title" class="form-control rounded-3 py-2 @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Enter a catchy title" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Slug</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted small">{{ url('/posts') }}/</span>
                        <input type="text" name="slug" class="form-control rounded-3 py-2 border-start-0 @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="url-slug-here">
                    </div>
                    <small class="text-muted mt-2 d-block">Leave blank to auto-generate from title</small>
                    @error('slug')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="mb-0">
                    <label class="form-label fw-bold">Body Content</label>
                    <textarea name="body" class="form-control rounded-3 @error('body') is-invalid @enderror" rows="15" placeholder="Tell your story..." required>{{ old('body') }}</textarea>
                    @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="admin-card p-4 mb-4">
                <h5 class="fw-bold mb-4">Publish Information</h5>
                
                <div class="mb-4">
                    <label class="form-label fw-bold small">Status</label>
                    <select name="status" id="status-select" class="form-select rounded-3 py-2 @error('status') is-invalid @enderror">
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div id="schedule-container" class="mb-4 {{ old('status') == 'scheduled' ? '' : 'd-none' }}">
                    <label class="form-label fw-bold small">Publish Date & Time</label>
                    <input type="datetime-local" name="publish_at" class="form-control rounded-3 py-2 @error('publish_at') is-invalid @enderror" value="{{ old('publish_at') }}">
                    @error('publish_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <hr class="my-4">

                <div class="mb-4">
                    <label class="form-label fw-bold small">Cover Image (Upload)</label>
                    <input type="file" name="image" class="form-control rounded-3 py-2 @error('image') is-invalid @enderror">
                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <hr class="flex-grow-1 my-0">
                        <span class="mx-2 small text-muted text-uppercase fw-bold">OR</span>
                        <hr class="flex-grow-1 my-0">
                    </div>
                    <label class="form-label fw-bold small text-primary">Remote Image URL</label>
                    <input type="url" name="image_url" class="form-control rounded-3 py-2 @error('image_url') is-invalid @enderror" value="{{ old('image_url') }}" placeholder="https://example.com/image.jpg">
                    <small class="text-muted d-block mt-1">We will download and save it to storage automatically.</small>
                    @error('image_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary py-2 fw-bold rounded-3 shadow-sm">Save & Exit</button>
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-light py-2 fw-bold rounded-3 border">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    document.getElementById('status-select').addEventListener('change', function() {
        const container = document.getElementById('schedule-container');
        if (this.value === 'scheduled') {
            container.classList.remove('d-none');
        } else {
            container.classList.add('d-none');
        }
    });
</script>
@endsection
