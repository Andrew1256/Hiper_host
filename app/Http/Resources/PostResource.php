<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'slug'           => $this->slug,
            'body'           => $this->body,
            'image_url'      => $this->image_url,
            'status'         => $this->status,
            'author'         => $this->whenLoaded('user', fn() => $this->user->name),
            'published_at'   => $this->published_at?->toISOString(),
            'views_count'    => $this->views_count,
            'comments_count' => $this->comments_count,
        ];
    }
}
