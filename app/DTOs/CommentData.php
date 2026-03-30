<?php

namespace App\DTOs;

use Illuminate\Http\Request;

final readonly class CommentData
{
    public function __construct(
        public string $name,
        public string $body,
        public string $ip,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
            body: $request->input('body'),
            ip: $request->ip(),
        );
    }
}
