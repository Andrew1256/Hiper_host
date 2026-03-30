<?php

namespace App\Services;

use App\DTOs\PostData;
use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class PostActionService
{
    public function __construct(
        protected PostRepositoryInterface $postRepo
    ) {}

    public function createPost(PostData $data): Post
    {
        $attributes = $data->toArray();
        $attributes['image'] = $this->handleImage($data);

        if ($data->status === Post::STATUS_PUBLISHED) {
            $attributes['published_at'] = now();
        }

        return $this->postRepo->create($attributes);
    }

    public function updatePost(Post $post, PostData $data): Post
    {
        $attributes = $data->toArray();

        // Slug cannot be changed after publication
        if ($post->wasPublished()) {
            unset($attributes['slug']);
        }

        $newImage = $this->handleImage($data);
        if ($newImage) {
            $this->deleteImageFile($post->image);
            $attributes['image'] = $newImage;
        }

        // Set published_at when first transitioning to published
        if ($data->status === Post::STATUS_PUBLISHED && $post->status !== Post::STATUS_PUBLISHED) {
            $attributes['published_at'] = now();
        }

        return $this->postRepo->update($post, $attributes);
    }

    public function deletePost(Post $post): void
    {
        $this->deleteImageFile($post->image);
        $this->postRepo->delete($post);
    }

    protected function handleImage(PostData $data): ?string
    {
        // 1. Handle uploaded file (priority)
        if ($data->image) {
            return $data->image->store('posts', 'public');
        }

        // 2. Handle remote image URL
        if ($data->imageUrl) {
            try {
                $contents = file_get_contents($data->imageUrl);
                if ($contents) {
                    $extension = pathinfo($data->imageUrl, PATHINFO_EXTENSION) ?: 'jpg';
                    $filename = 'posts/' . \Illuminate\Support\Str::random(10) . '.' . $extension;
                    
                    Storage::disk('public')->put($filename, $contents);
                    return $filename;
                }
            } catch (\Exception $e) {
                // If download fails, store the URL directly as fallback ("хоча б якось")
                return $data->imageUrl;
            }
        }

        return null;
    }

    protected function deleteImageFile(?string $path): void
    {
        if ($path && !str_starts_with($path, 'http')) {
            Storage::disk('public')->delete($path);
        }
    }
}
