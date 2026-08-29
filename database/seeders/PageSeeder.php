<?php

namespace Database\Seeders;

use App\Models\ContentBlock;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\User;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->first();

        if (! $user) {
            $this->command?->error('No users found. Run UserSeeder first (set SEED_ADMIN_PASSWORD in production).');

            return;
        }

        $homePage = Page::query()->updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Home',
                'short_description' => 'Welcome to JR Couple — grass, mobiles, real estate, and hair',
                'is_active' => true,
                'display_order' => 0,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        );

        $aboutPage = Page::query()->updateOrCreate(
            ['slug' => 'about'],
            [
                'title' => 'About',
                'short_description' => 'Learn more about JR Couple',
                'is_active' => true,
                'display_order' => 1,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        );

        $heroFeatures = $this->section($homePage->id, 'hero Features', [
            'subtitle' => 'Discover Our Key Features',
            'display_order' => 1,
        ], $user->id);

        $aboutFeatures = $this->section($homePage->id, 'About Features', [
            'subtitle' => 'Who We Are',
            'display_order' => 2,
        ], $user->id);

        $videoSection = $this->section($homePage->id, 'Video Section', [
            'subtitle' => 'Watch Our Introduction',
            'display_order' => 3,
        ], $user->id);

        $aboutSection = $this->section($aboutPage->id, 'About Us', [
            'subtitle' => 'Learn More About Us',
            'display_order' => 4,
        ], $user->id);

        ContentBlock::query()->updateOrCreate(
            ['slug' => 'key-features'],
            [
                'section_id' => $heroFeatures->id,
                'type' => 'list',
                'title' => 'Key Features',
                'list_items' => [
                    [
                        'title' => 'Professionalism',
                        'icon' => 'bi bi-shield-check',
                        'description' => 'Our team consists of experienced leaders recognized regionally and...',
                    ],
                    [
                        'title' => 'Client-Centric Approach',
                        'icon' => 'bi bi-person-heart',
                        'description' => 'Getting our clients what they deserve is our mission. We prioritize...',
                    ],
                    [
                        'title' => 'Regional Impact',
                        'icon' => 'bi bi-globe',
                        'description' => 'We address local development challenges using effective...',
                    ],
                ],
                'display_order' => 1,
                'is_active' => true,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        );

        ContentBlock::query()->updateOrCreate(
            ['slug' => 'veritas-afrika-co-ltd'],
            [
                'section_id' => $aboutFeatures->id,
                'type' => 'image',
                'title' => 'JR Couple',
                'subtitle' => 'Who We Are',
                'short_description' => '',
                'content' => '<p>JR Couple brings together JR Ketema, JR Mobile, JR Real Estate, and Ruties Hair — shop the store or browse services to see each brand.</p>',
                'display_order' => 2,
                'is_active' => true,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        );

        ContentBlock::query()->updateOrCreate(
            ['slug' => 'video-section'],
            [
                'section_id' => $videoSection->id,
                'type' => 'video',
                'title' => 'Video Section',
                'subtitle' => 'Working Process',
                'short_description' => 'Company market share in the domestic market',
                'content' => '',
                'video_url' => 'https://www.youtube.com/watch?v=MDF2vmMFtQg&list=RDzHdAB4xj3GI&index=7',
                'display_order' => 3,
                'is_active' => true,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        );

        ContentBlock::query()->updateOrCreate(
            ['slug' => 'video-thumbnail'],
            [
                'section_id' => $videoSection->id,
                'type' => 'image',
                'title' => 'Video Thumbnail',
                'subtitle' => '',
                'short_description' => '',
                'content' => '',
                'display_order' => 4,
                'is_active' => true,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        );

        ContentBlock::query()->updateOrCreate(
            ['slug' => 'video-details'],
            [
                'section_id' => $videoSection->id,
                'type' => 'list',
                'title' => 'Video Details',
                'subtitle' => '',
                'short_description' => 'Serving with expertise in industries as one of World leading Corporation ',
                'list_items' => [
                    [
                        'title' => 'Available To All Industries',
                        'icon' => '',
                        'description' => 'Our specialists offer manufacturing of complex machined precision parts, as well as turning and milling, to support a wide host of industries.',
                    ],
                ],
                'metadata' => [
                    'data1' => 'Manufacturing',
                    'data2' => 'Pharmaceutical',
                    'data3' => 'Defense',
                    'data4' => 'Off-Road / Petroleum',
                    'data5' => 'Nuclear',
                    'data6' => 'Automotive',
                ],
                'display_order' => 5,
                'is_active' => true,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        );

        ContentBlock::query()->updateOrCreate(
            ['slug' => 'about-section-1'],
            [
                'section_id' => $aboutSection->id,
                'type' => 'image',
                'title' => 'About Section 1',
                'subtitle' => 'About Us',
                'short_description' => '',
                'content' => '<p><strong>JR Couple</strong> is a family of brands for artificial grass, phones, property, and hair — built to be easy to visit and easy to trust.</p>',
                'display_order' => 6,
                'is_active' => true,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        );

        ContentBlock::query()->updateOrCreate(
            ['slug' => 'about-section-2'],
            [
                'section_id' => $aboutSection->id,
                'type' => 'image',
                'title' => 'About Section 2',
                'subtitle' => 'About Us',
                'short_description' => '',
                'content' => '<h2><strong>professional</strong></h2><p><em style="text-decoration: underline;">&nbsp;consultant</em>s specializing in a wide range of civil engineering works. We provide expert services to government, non-government, and private-sector customers.</p>',
                'display_order' => 7,
                'is_active' => true,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        );

        ContentBlock::query()->updateOrCreate(
            ['slug' => 'stats'],
            [
                'section_id' => $heroFeatures->id,
                'type' => 'list',
                'title' => 'Impact Stats',
                'subtitle' => 'By the numbers',
                'list_items' => [
                    ['label' => 'Projects delivered', 'value' => 48, 'suffix' => '+'],
                    ['label' => 'Years of practice', 'value' => 12, 'suffix' => '+'],
                    ['label' => 'Specialists', 'value' => 25, 'suffix' => '+'],
                    ['label' => 'Partner agencies', 'value' => 18, 'suffix' => '+'],
                ],
                'display_order' => 8,
                'is_active' => true,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        );
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function section(int $pageId, string $title, array $attributes, int $userId): PageSection
    {
        return PageSection::query()->updateOrCreate(
            ['page_id' => $pageId, 'title' => $title],
            array_merge($attributes, [
                'is_active' => true,
                'created_by' => $userId,
                'updated_by' => $userId,
            ])
        );
    }
}
