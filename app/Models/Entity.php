<?php

namespace App\Models;

use App\Enums\EntityTypeEnum;
use App\Enums\PostSourceEnum;
use App\Enums\StatusEnum;
use App\Traits\HasUserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Entity extends Model implements HasMedia
{
    use HasUserStamps, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'category',
        'source',
        'link',
        'description',
        'price',
        'price_label',
        'is_negotiable',
        'order',
        'is_featured',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $appends = [
        'image_url',
    ];

    protected $casts = [
        'order' => 'integer',
        'price' => 'decimal:2',
        'is_negotiable' => 'boolean',
        'is_featured' => 'boolean',
        'type' => EntityTypeEnum::class,
        'source' => PostSourceEnum::class,
        'status' => StatusEnum::class,
    ];

    protected static function booted(): void
    {
        static::saving(function (Entity $entity): void {
            if (! $entity->is_featured || $entity->type !== EntityTypeEnum::product) {
                return;
            }

            static::query()
                ->where('type', EntityTypeEnum::product)
                ->when($entity->exists, fn ($query) => $query->whereKeyNot($entity->id))
                ->update(['is_featured' => false]);
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function activeReviews(): HasMany
    {
        return $this->reviews()->where('status', StatusEnum::active);
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile();

        $this->addMediaCollection('post_media');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        if ($media && ! str_starts_with((string) $media->mime_type, 'image/')) {
            return;
        }
    }

    public function getImageUrlAttribute(): ?string
    {
        $url = $this->getFirstMediaUrl('image');

        return $url !== '' ? $url : null;
    }

    public function isSocialPost(): bool
    {
        return $this->type === EntityTypeEnum::post
            && $this->source === PostSourceEnum::social;
    }

    public function isHostedPost(): bool
    {
        return $this->type === EntityTypeEnum::post
            && $this->source !== PostSourceEnum::social;
    }

    public function socialRedirectUrl(): ?string
    {
        $url = trim((string) $this->link);

        if ($url === '' || ! preg_match('#^https?://#i', $url)) {
            return null;
        }

        return $url;
    }

    public function socialPlatform(): ?string
    {
        $host = strtolower((string) parse_url((string) $this->link, PHP_URL_HOST));
        $host = preg_replace('/^www\./', '', $host) ?: '';

        return match (true) {
            $host === '' => null,
            str_contains($host, 'tiktok') => 'TikTok',
            str_contains($host, 'instagram') || $host === 'instagr.am' => 'Instagram',
            str_contains($host, 'telegram') || $host === 't.me' => 'Telegram',
            str_contains($host, 'youtube') || $host === 'youtu.be' => 'YouTube',
            str_contains($host, 'facebook') || $host === 'fb.com' || $host === 'fb.watch' => 'Facebook',
            $host === 'x.com' || str_contains($host, 'twitter') => 'X',
            str_contains($host, 'linkedin') => 'LinkedIn',
            str_contains($host, 'whatsapp') => 'WhatsApp',
            default => 'Social',
        };
    }

    public function socialIconClass(): string
    {
        return match ($this->socialPlatform()) {
            'TikTok' => 'bi-tiktok',
            'Instagram' => 'bi-instagram',
            'Telegram' => 'bi-telegram',
            'YouTube' => 'bi-youtube',
            'Facebook' => 'bi-facebook',
            'X' => 'bi-twitter-x',
            'LinkedIn' => 'bi-linkedin',
            'WhatsApp' => 'bi-whatsapp',
            default => 'bi-box-arrow-up-right',
        };
    }

    public function firstPostMedia(): ?Media
    {
        return $this->getFirstMedia('post_media') ?? $this->getFirstMedia('image');
    }

    public function firstPostMediaUrl(): ?string
    {
        $url = $this->getFirstMediaUrl('post_media') ?: $this->getFirstMediaUrl('image');

        return $url !== '' ? $url : null;
    }

    public function firstPostMediaIsVideo(): bool
    {
        $media = $this->firstPostMedia();

        return $media !== null && str_starts_with((string) $media->mime_type, 'video/');
    }

    public function formattedPrice(): string
    {
        if ($this->price === null) {
            return 'Ask in store';
        }

        $amount = 'ETB '.number_format((float) $this->price, 0);

        if ($this->price_label) {
            $amount .= ' '.$this->price_label;
        }

        return $amount;
    }

    public function priceAmount(): ?float
    {
        return $this->price !== null ? (float) $this->price : null;
    }

    public function blogChannel(): string
    {
        if ($this->isSocialPost()) {
            return strtolower($this->socialPlatform() ?: 'social');
        }

        return 'tips';
    }

    public function postedOn(): string
    {
        return $this->created_at?->format('M j, Y') ?? '';
    }

    public function readingMinutes(): int
    {
        $words = str_word_count(strip_tags((string) $this->description));

        return max(1, (int) ceil($words / 180));
    }
}
