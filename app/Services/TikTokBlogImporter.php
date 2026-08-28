<?php

namespace App\Services;

use App\Enums\EntityTypeEnum;
use App\Enums\PostSourceEnum;
use App\Enums\StatusEnum;
use App\Models\Entity;
use App\Models\Service;
use App\Models\SocialRef;
use Illuminate\Support\Str;
use RuntimeException;

class TikTokBlogImporter
{
    public function __construct(private TikTokOEmbedService $oembed)
    {
    }

    public function rememberedHandle(): ?string
    {
        $ref = $this->tiktokSocialRef();

        return $ref ? $this->oembed->normalizeHandle($ref->link) : null;
    }

    public function rememberHandle(?string $handle): ?SocialRef
    {
        $handle = $this->oembed->normalizeHandle($handle);

        if ($handle === null) {
            return $this->tiktokSocialRef();
        }

        $url = $this->oembed->accountUrl($handle);
        $service = $this->serviceForTikTokHandle($handle);

        if ($service) {
            if ($service->tiktok_url !== $url) {
                $service->update(['tiktok_url' => $url]);
            }

            return $this->tiktokSocialRef();
        }

        $ref = $this->tiktokSocialRef();

        if ($ref) {
            $existing = $this->oembed->normalizeHandle($ref->link);

            if ($existing === null || $existing === $handle) {
                $ref->update([
                    'link' => $url,
                    'status' => StatusEnum::active,
                ]);
            }

            return $ref->refresh();
        }

        return SocialRef::query()->create([
            'title' => 'TikTok',
            'link' => $url,
            'icon_class' => 'bi bi-tiktok',
            'order' => (int) (SocialRef::query()->max('order') ?: 0) + 1,
            'status' => StatusEnum::active,
        ]);
    }

    /**
     * @return array<string, string>
     */
    public function accountOptions(): array
    {
        $options = [];
        $main = $this->rememberedHandle() ?: 'ruthandjonas';
        $options[$main] = 'JR Couple (@'.$main.')';

        foreach (Service::query()->orderBy('order')->get() as $service) {
            $handle = $this->oembed->normalizeHandle($service->tiktok_url);

            if ($handle) {
                $options[$handle] = $service->title.' (@'.$handle.')';
            }
        }

        return $options;
    }

    /**
     * @param  array{
     *     video_url: string,
     *     post_to_website?: bool,
     *     use_exact_caption?: bool,
     *     account?: ?string,
     *     caption?: ?string,
     *     name?: ?string
     * }  $input
     */
    public function import(array $input): ?Entity
    {
        $this->rememberHandle($input['account'] ?? null);

        $videoUrl = trim((string) ($input['video_url'] ?? ''));

        if ($videoUrl === '' || ! $this->oembed->isTikTokUrl($videoUrl)) {
            throw new RuntimeException('Paste a public TikTok video URL.');
        }

        if (! ($input['post_to_website'] ?? true)) {
            return null;
        }

        $existing = Entity::query()
            ->where('type', EntityTypeEnum::post)
            ->where('link', $videoUrl)
            ->first();

        if ($existing) {
            throw new RuntimeException('That TikTok is already on the blog.');
        }

        $meta = $this->oembed->fetch($videoUrl);
        $fetchedCaption = trim((string) ($meta['caption'] ?? ''));
        $useExact = (bool) ($input['use_exact_caption'] ?? true);
        $caption = $useExact
            ? ($fetchedCaption !== '' ? $fetchedCaption : trim((string) ($input['caption'] ?? '')))
            : trim((string) ($input['caption'] ?? ''));

        $name = trim((string) ($input['name'] ?? ''));

        if ($name === '') {
            $name = $this->titleFromCaption($caption !== '' ? $caption : 'TikTok post');
        }

        $post = Entity::query()->create([
            'name' => Str::limit($name, 255),
            'type' => EntityTypeEnum::post,
            'source' => PostSourceEnum::social,
            'category' => 'TikTok',
            'link' => $videoUrl,
            'description' => $caption !== '' ? $caption : null,
            'order' => (int) (Entity::query()->where('type', EntityTypeEnum::post)->max('order') ?: 0) + 1,
            'status' => StatusEnum::active,
        ]);

        $thumbnail = trim((string) ($meta['thumbnail_url'] ?? ''));

        if ($thumbnail !== '') {
            try {
                $post->addMediaFromUrl($thumbnail)->toMediaCollection('image');
            } catch (\Throwable) {
                // TikTok thumbnails are optional; the social card still works without one.
            }
        }

        return $post;
    }

    public function titleFromCaption(string $caption): string
    {
        $line = trim(Str::before($caption, "\n"));

        return Str::limit($line !== '' ? $line : $caption, 80);
    }

    private function tiktokSocialRef(): ?SocialRef
    {
        return SocialRef::query()
            ->where('title', 'TikTok')
            ->orderBy('order')
            ->first()
            ?? SocialRef::query()
                ->where('link', 'like', '%tiktok.com%')
                ->orderBy('order')
                ->first();
    }

    private function serviceForTikTokHandle(string $handle): ?Service
    {
        foreach (Service::query()->get() as $service) {
            if ($this->oembed->normalizeHandle($service->tiktok_url) === $handle) {
                return $service;
            }
        }

        return null;
    }
}
