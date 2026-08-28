<?php

namespace Tests\Feature;

use App\Enums\EntityTypeEnum;
use App\Enums\PostSourceEnum;
use App\Enums\StatusEnum;
use App\Models\Entity;
use App\Models\Organization;
use App\Models\SocialRef;
use App\Services\TikTokBlogImporter;
use App\Services\TikTokOEmbedService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TikTokBlogImportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Organization::query()->create([
            'title' => 'JR Test',
            'status' => 'active',
        ]);
    }

    public function test_oembed_reads_the_tiktok_caption(): void
    {
        Http::fake([
            'https://www.tiktok.com/oembed*' => Http::response([
                'title' => 'Turf that stays green all year #jrketema',
                'author_name' => 'JR Couple',
                'author_url' => 'https://www.tiktok.com/@jrcouple',
                'thumbnail_url' => 'https://example.com/thumb.jpg',
            ]),
        ]);

        $meta = app(TikTokOEmbedService::class)
            ->fetch('https://www.tiktok.com/@jrcouple/video/123');

        $this->assertNotNull($meta);
        $this->assertSame('Turf that stays green all year #jrketema', $meta['caption']);
        $this->assertSame('jrcouple', $meta['handle']);
    }

    public function test_import_posts_the_link_and_keeps_the_caption(): void
    {
        Http::fake([
            'https://www.tiktok.com/oembed*' => Http::response([
                'title' => 'Turf that stays green all year #jrketema',
                'author_name' => 'JR Couple',
                'author_url' => 'https://www.tiktok.com/@jrcouple',
                'thumbnail_url' => '',
            ]),
        ]);

        $post = app(TikTokBlogImporter::class)->import([
            'account' => '@jrcouple',
            'video_url' => 'https://www.tiktok.com/@jrcouple/video/123',
            'post_to_website' => true,
            'use_exact_caption' => true,
        ]);

        $this->assertNotNull($post);
        $this->assertTrue($post->isSocialPost());
        $this->assertSame('https://www.tiktok.com/@jrcouple/video/123', $post->link);
        $this->assertSame('Turf that stays green all year #jrketema', $post->description);
        $this->assertSame(
            'jrcouple',
            app(TikTokOEmbedService::class)->normalizeHandle(
                SocialRef::query()->where('title', 'TikTok')->value('link')
            )
        );

        $this->get(route('blog.index'))
            ->assertOk()
            ->assertSee('Turf that stays green all year #jrketema');
    }

    public function test_declining_to_post_saves_the_account_only(): void
    {
        $result = app(TikTokBlogImporter::class)->import([
            'account' => 'jrcouple',
            'video_url' => 'https://www.tiktok.com/@jrcouple/video/123',
            'post_to_website' => false,
            'use_exact_caption' => true,
        ]);

        $this->assertNull($result);
        $this->assertSame(0, Entity::query()->where('type', EntityTypeEnum::post)->count());
        $this->assertDatabaseHas('social_refs', [
            'title' => 'TikTok',
            'link' => 'https://www.tiktok.com/@jrcouple',
        ]);
    }

    public function test_custom_caption_is_used_when_exact_caption_is_off(): void
    {
        Http::fake([
            'https://www.tiktok.com/oembed*' => Http::response([
                'title' => 'Original TikTok words',
                'author_name' => 'JR',
                'author_url' => 'https://www.tiktok.com/@jr',
                'thumbnail_url' => '',
            ]),
        ]);

        $post = app(TikTokBlogImporter::class)->import([
            'account' => 'jr',
            'video_url' => 'https://www.tiktok.com/@jr/video/99',
            'post_to_website' => true,
            'use_exact_caption' => false,
            'caption' => 'Come see this in the store.',
            'name' => 'Store drop',
        ]);

        $this->assertSame('Come see this in the store.', $post->description);
        $this->assertSame('Store drop', $post->name);
        $this->assertSame(PostSourceEnum::social, $post->source);
        $this->assertSame(StatusEnum::active, $post->status);
    }

    public function test_cover_downloader_saves_the_image(): void
    {
        Http::fake([
            'https://cdn.example/cover.jpg' => Http::response('jpeg-bytes', 200, [
                'Content-Type' => 'image/jpeg',
            ]),
        ]);

        $path = app(\App\Services\TikTokCoverDownloader::class)
            ->download('https://cdn.example/cover.jpg');

        $this->assertNotNull($path);
        $this->assertFileExists($path);
        $this->assertSame('jpeg-bytes', file_get_contents($path));
        unlink($path);
    }
}
