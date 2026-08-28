<?php

namespace Tests\Feature;

use App\Enums\EntityTypeEnum;
use App\Enums\PostSourceEnum;
use App\Enums\StatusEnum;
use App\Models\Entity;
use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogPostTest extends TestCase
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

    public function test_blog_index_lists_posts(): void
    {
        Entity::query()->create([
            'name' => 'Hosted turf tip',
            'type' => EntityTypeEnum::post,
            'source' => PostSourceEnum::media,
            'description' => 'Keep turf clean.',
            'order' => 1,
            'status' => StatusEnum::active,
        ]);

        $response = $this->get(route('blog.index'));

        $response->assertOk();
        $response->assertSee('Hosted turf tip');
        $response->assertSee('Keep turf clean.');
    }

    public function test_social_post_redirects_to_the_original_url(): void
    {
        $post = Entity::query()->create([
            'name' => 'Watch on TikTok',
            'type' => EntityTypeEnum::post,
            'source' => PostSourceEnum::social,
            'link' => 'https://www.tiktok.com/@jr/video/123',
            'description' => 'Site walkthrough.',
            'order' => 1,
            'status' => StatusEnum::active,
        ]);

        $index = $this->get(route('blog.index'));
        $index->assertOk();
        $index->assertSee('Watch on TikTok');
        $index->assertSee('Open on TikTok');

        $this->get(route('blog.show', $post))
            ->assertRedirect('https://www.tiktok.com/@jr/video/123');
    }

    public function test_hosted_post_is_shown_on_the_site(): void
    {
        $post = Entity::query()->create([
            'name' => 'Phone buying checklist',
            'type' => EntityTypeEnum::post,
            'source' => PostSourceEnum::media,
            'description' => 'Battery, camera, and warranty.',
            'order' => 1,
            'status' => StatusEnum::active,
        ]);

        $this->get(route('blog.show', $post))
            ->assertOk()
            ->assertSee('Phone buying checklist')
            ->assertSee('Battery, camera, and warranty.');
    }
}
