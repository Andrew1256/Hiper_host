<?php

namespace App\DTOs;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

final readonly class PostData
{
    public function __construct(
        public string $title,
        public string $slug,
        public string $body,
        public string $status,
        public ?int $user_id = null,
        public ?UploadedFile $image = null,
        public ?string $imageUrl = null,
        public ?string $publish_at = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $title = $request->string('title')->toString();
        $slug = $request->string('slug')->toString();

        if (empty($slug) && ! empty($title)) {
            $slug = \Illuminate\Support\Str::slug($title);
        }

        return new self(
            title: $title,
            slug: $slug,
            body: $request->string('body')->toString(),
            status: $request->string('status')->toString(),
            user_id: $request->user()?->id,
            image: $request->file('image'),
            imageUrl: $request->string('image_url')->toString(),
            publish_at: $request->input('publish_at'),
        );
    }

    /**
     * Convert to array for DB operations.
     * Only includes scalar fields — image path is handled by the service.
     */
    public function toArray(): array
    {
        $data = [
            'title'   => $this->title,
            'slug'    => $this->slug,
            'body'    => $this->body,
            'status'  => $this->status,
            'user_id' => $this->user_id,
        ];

        // Include publish_at only when set (allows explicitly clearing it too)
        if ($this->publish_at !== null) {
            $data['publish_at'] = $this->publish_at;
        }

        return $data;
    }
}
