<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;

    public const STATUS_DRAFT      = 'draft';
    public const STATUS_SCHEDULED  = 'scheduled';
    public const STATUS_PUBLISHED  = 'published';

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'body',
        'image',
        'status',
        'published_at',
        'publish_at',
        // views_count and comments_count are EXCLUDED from mass-assignment.
        // They must only be modified via atomic increment() calls.
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'publish_at'   => 'datetime',
        'views_count'  => 'integer',
        'comments_count' => 'integer',
    ];

    /* ----------------------------------------------------------------
     * Relationships
     * ---------------------------------------------------------------- */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /* ----------------------------------------------------------------
     * Scopes
     * ---------------------------------------------------------------- */

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeScheduled(Builder $query): Builder
    {
        return $query
            ->where('status', self::STATUS_SCHEDULED)
            ->where('publish_at', '<=', now());
    }

    public function scopeSorted(Builder $query, ?string $sort): Builder
    {
        return match ($sort) {
            'most_commented' => $query->orderByDesc('comments_count'),
            'most_viewed'    => $query->orderByDesc('views_count'),
            default          => $query->orderByDesc('published_at'),
        };
    }

    public function scopeByAuthor(Builder $query, ?int $authorId): Builder
    {
        return $authorId ? $query->where('user_id', $authorId) : $query;
    }

    /* ----------------------------------------------------------------
     * Helpers
     * ---------------------------------------------------------------- */

    public function wasPublished(): bool
    {
        return $this->published_at !== null;
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->url($this->image);
    }
}
