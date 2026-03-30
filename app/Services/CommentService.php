<?php

namespace App\Services;

use App\DTOs\CommentData;
use App\Events\CommentCreated;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class CommentService
{
    public function createComment(Post $post, CommentData $data): Comment
    {
        $rateKey = "comment_rate_{$data->ip}";

        // Requirement: "no more than 1 per 30s"
        $executed = RateLimiter::attempt(
            $rateKey,
            maxAttempts: 1,
            callback: fn() => true,
            decaySeconds: 30
        );

        if (! $executed) {
            $seconds = RateLimiter::availableIn($rateKey);
            throw new TooManyRequestsHttpException(
                retryAfter: $seconds,
                message: "Please wait {$seconds} seconds before posting another comment (limit: 1 per 30s)."
            );
        }

        $comment = $post->comments()->create([
            'name'       => $data->name,
            'body'       => $data->body,
            'ip_address' => $data->ip,
        ]);

        CommentCreated::dispatch($comment);

        return $comment;
    }
}
