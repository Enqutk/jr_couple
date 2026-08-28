<?php

namespace Database\Seeders;

use App\Models\ContentBlock;
use App\Models\PageSection;
use App\Models\User;
use Illuminate\Database\Seeder;

class AboutContentSeeder extends Seeder
{
    public function run(): void
    {
        $userId = User::query()->value('id');
        $sectionId = PageSection::query()->value('id');

        ContentBlock::query()->updateOrCreate(
            ['slug' => 'veritas-afrika-co-ltd'],
            [
                'section_id' => ContentBlock::query()->where('slug', 'veritas-afrika-co-ltd')->value('section_id') ?? $sectionId,
                'type' => 'image',
                'title' => 'Welcome to JR Couple',
                'subtitle' => 'Who we are',
                'short_description' => '',
                'content' => '<p>JR brings together practical businesses: <strong>JR Ketema</strong> (artificial grass), <strong>JR Mobile</strong> (phones &amp; accessories), <strong>JR Real Estate</strong>, and <strong>Ruties Hair</strong>. Visit the store to shop by category, or browse services to learn what each brand offers.</p>',
                'display_order' => 2,
                'is_active' => true,
                'created_by' => $userId,
                'updated_by' => $userId,
            ]
        );

        ContentBlock::query()->updateOrCreate(
            ['slug' => 'key-features'],
            [
                'section_id' => ContentBlock::query()->where('slug', 'key-features')->value('section_id') ?? $sectionId,
                'type' => 'list',
                'title' => 'Why JR',
                'list_items' => [
                    [
                        'title' => 'Three brands',
                        'icon' => 'bi bi-grid',
                        'description' => 'Grass, mobiles, property, and hair under one trusted name.',
                    ],
                    [
                        'title' => 'Neighbourhood store',
                        'icon' => 'bi bi-shop',
                        'description' => 'Browse categories online, then confirm stock with us.',
                    ],
                    [
                        'title' => 'Clear advice',
                        'icon' => 'bi bi-chat-dots',
                        'description' => 'Friendly guidance whether you are buying turf, a phone, or a home.',
                    ],
                    [
                        'title' => 'Local & reachable',
                        'icon' => 'bi bi-geo-alt',
                        'description' => 'Message or visit — we keep contact simple.',
                    ],
                ],
                'display_order' => 1,
                'is_active' => true,
                'created_by' => $userId,
                'updated_by' => $userId,
            ]
        );

        ContentBlock::query()->updateOrCreate(
            ['slug' => 'about-section-1'],
            [
                'section_id' => ContentBlock::query()->where('slug', 'about-section-1')->value('section_id') ?? $sectionId,
                'type' => 'image',
                'title' => 'Our brands',
                'subtitle' => 'About JR',
                'content' => '<p>Each line is focused: Ketema on turf, Mobile on devices, Real Estate on listings, and Ruties Hair on wigs, extensions, and care.</p>',
                'display_order' => 6,
                'is_active' => true,
                'created_by' => $userId,
                'updated_by' => $userId,
            ]
        );

        ContentBlock::query()->updateOrCreate(
            ['slug' => 'about-section-2'],
            [
                'section_id' => ContentBlock::query()->where('slug', 'about-section-2')->value('section_id') ?? $sectionId,
                'type' => 'image',
                'title' => 'The store',
                'subtitle' => 'About JR',
                'content' => '<p>The JR Store groups products under those service categories — a neighbourhood shop feel with clear shelves by brand, including Ruties Hair.</p>',
                'display_order' => 7,
                'is_active' => true,
                'created_by' => $userId,
                'updated_by' => $userId,
            ]
        );
    }
}
