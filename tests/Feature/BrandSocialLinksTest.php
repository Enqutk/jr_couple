<?php

namespace Tests\Feature;

use App\Enums\StatusEnum;
use App\Models\Organization;
use App\Models\Service;
use App\Models\SocialRef;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandSocialLinksTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Organization::query()->create([
            'title' => 'JR Test',
            'status' => 'active',
        ]);

        SocialRef::query()->create([
            'title' => 'Instagram',
            'link' => 'https://www.instagram.com/j.r._c.o.u.p.l.e/',
            'icon_class' => 'bi bi-instagram',
            'order' => 1,
            'status' => StatusEnum::active,
        ]);
    }

    public function test_mobile_and_hair_pages_show_their_social_accounts(): void
    {
        Service::query()->create([
            'slug' => 'jr-mobile',
            'title' => 'JR Mobile',
            'instagram_url' => 'https://www.instagram.com/j.r_mobiles/',
            'tiktok_url' => 'https://www.tiktok.com/@jr.m.o.b.i.l.e',
            'order' => 1,
            'status' => StatusEnum::active,
        ]);

        Service::query()->create([
            'slug' => 'ruties-hair',
            'title' => 'Ruties Hair',
            'instagram_url' => 'https://www.instagram.com/ruthishair/',
            'tiktok_url' => 'https://www.tiktok.com/@ruthishair',
            'order' => 2,
            'status' => StatusEnum::active,
        ]);

        $this->get(route('services.show', 'jr-mobile'))
            ->assertOk()
            ->assertSee('https://www.instagram.com/j.r_mobiles/', false)
            ->assertSee('https://www.tiktok.com/@jr.m.o.b.i.l.e', false);

        $this->get(route('services.show', 'ruties-hair'))
            ->assertOk()
            ->assertSee('https://www.instagram.com/ruthishair/', false)
            ->assertSee('https://www.tiktok.com/@ruthishair', false);
    }

    public function test_ketema_has_no_brand_social_links(): void
    {
        Service::query()->create([
            'slug' => 'jr-ketema',
            'title' => 'JR Ketema',
            'order' => 1,
            'status' => StatusEnum::active,
        ]);

        $html = $this->get(route('services.show', 'jr-ketema'))
            ->assertOk()
            ->getContent();

        $this->assertStringNotContainsString('hz-service-detail-hero-social', $html);
    }
}
