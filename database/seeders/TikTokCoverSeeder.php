<?php

namespace Database\Seeders;

use App\Enums\EntityTypeEnum;
use App\Enums\PostSourceEnum;
use App\Enums\StatusEnum;
use App\Models\Entity;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\MediaLibrary\HasMedia;

class TikTokCoverSeeder extends Seeder
{
    public function run(): void
    {
        $covers = public_path('assets/images/jr/covers');
        $userId = User::query()->value('id');

        $this->attachServices($covers);
        $this->attachBlogPosts($covers, $userId);
    }

    private function attachServices(string $covers): void
    {
        $this->attachFile(
            Service::query()->where('slug', 'jr-ketema')->first(),
            'main_image',
            $covers,
            'ketema.jpg'
        );

        $this->attachFile(
            Service::query()->where('slug', 'jr-mobile')->first(),
            'main_image',
            $covers,
            'mobile-1.jpg'
        );

        $this->attachFile(
            Service::query()->where('slug', 'jr-real-estate')->first(),
            'main_image',
            $covers,
            'real-estate.jpg'
        );

        $this->attachFile(
            Service::query()->where('slug', 'ruties-hair')->first(),
            'main_image',
            $covers,
            'hair-avatar.jpg'
        );
    }

    private function attachBlogPosts(string $covers, mixed $userId): void
    {
        Entity::query()
            ->where('type', EntityTypeEnum::post)
            ->where('source', PostSourceEnum::media)
            ->where('order', '<', 10)
            ->increment('order', 10);

        $posts = [
            [
                'match_names' => ['JR Couple on TikTok', 'We are officially open'],
                'match_link' => '%/video/7635635118391971079%',
                'name' => 'We are officially open',
                'category' => 'TikTok',
                'link' => 'https://www.tiktok.com/@ruthandjonas/video/7635635118391971079',
                'description' => 'GOD did. We are officially open. Book your moments, let’s make them memorable.',
                'order' => 1,
                'cover' => 'couple-open.jpg',
            ],
            [
                'match_names' => ['JR Mobile on Instagram', 'Who’s team are you?'],
                'match_link' => '%tiktok.com/@jr.m.o.b.i.l.e%',
                'name' => 'Who’s team are you?',
                'category' => 'TikTok',
                'link' => 'https://www.tiktok.com/@jr.m.o.b.i.l.e/video/7678649767488130322',
                'description' => 'Who’s team are you? Phones and deals from JR Mobile — tap to watch on TikTok.',
                'order' => 2,
                'cover' => 'mobile-1.jpg',
            ],
            [
                'match_names' => ['Hair looks on Instagram', 'Ruth’s Hair on TikTok'],
                'match_link' => '%tiktok.com/@ruthishair%',
                'name' => 'Ruth’s Hair on TikTok',
                'category' => 'TikTok',
                'link' => 'https://www.tiktok.com/@ruthishair',
                'description' => 'Installs and looks from Ruth’s Hair — follow on TikTok.',
                'order' => 3,
                'cover' => 'hair-avatar.jpg',
            ],
            [
                'match_names' => ['Why artificial grass works in Addis sun', 'Presenting our newest product'],
                'match_link' => '%/video/7672753308582399250%',
                'name' => 'Presenting our newest product',
                'category' => 'TikTok',
                'link' => 'https://www.tiktok.com/@ruthandjonas/video/7672753308582399250',
                'description' => 'Presenting our newest product — JR Ketema. Tap to watch on TikTok.',
                'order' => 4,
                'cover' => 'ketema.jpg',
            ],
        ];

        foreach ($posts as $item) {
            $post = Entity::query()
                ->where('type', EntityTypeEnum::post)
                ->where(function ($query) use ($item) {
                    $query->whereIn('name', $item['match_names'])
                        ->orWhere('link', 'like', $item['match_link']);
                })
                ->first();

            $payload = [
                'name' => $item['name'],
                'type' => EntityTypeEnum::post,
                'source' => PostSourceEnum::social,
                'category' => $item['category'],
                'link' => $item['link'],
                'description' => $item['description'],
                'order' => $item['order'],
                'status' => StatusEnum::active,
            ];

            if ($post) {
                $post->update($payload);
            } else {
                $post = Entity::query()->create([
                    ...$payload,
                    'created_by' => $userId,
                    'updated_by' => $userId,
                ]);
            }

            $this->attachFile($post, 'image', $covers, $item['cover']);
        }
    }

    private function attachFile(?HasMedia $model, string $collection, string $dir, ?string $file): void
    {
        if (! $model || ! $file) {
            return;
        }

        $path = $dir.DIRECTORY_SEPARATOR.$file;

        if (! is_file($path)) {
            return;
        }

        $model->clearMediaCollection($collection);
        $model->addMedia($path)
            ->preservingOriginal()
            ->toMediaCollection($collection);
    }
}
