<?php

namespace Database\Seeders;

use App\Enums\EntityTypeEnum;
use App\Enums\PostSourceEnum;
use App\Enums\StatusEnum;
use App\Models\Entity;
use App\Models\User;
use Illuminate\Database\Seeder;

class EntitySeeder extends Seeder
{
    public function run(): void
    {
        $userId = User::query()->value('id');

        Entity::query()->forceDelete();

        $items = [
            // Store products — category = service slug
            [
                'name' => 'Home Lawn Turf 30mm',
                'type' => EntityTypeEnum::product,
                'category' => 'jr-ketema',
                'description' => 'Soft-touch residential artificial grass. Ideal for gardens and balconies.',
                'order' => 1,
            ],
            [
                'name' => 'Sports Pitch Turf 40mm',
                'type' => EntityTypeEnum::product,
                'category' => 'jr-ketema',
                'description' => 'Durable sports-grade turf for small pitches and training zones.',
                'order' => 2,
            ],
            [
                'name' => 'Playground Soft Turf',
                'type' => EntityTypeEnum::product,
                'category' => 'jr-ketema',
                'description' => 'Impact-friendly turf for kids’ play areas.',
                'order' => 3,
            ],
            [
                'name' => 'Android Everyday Phone',
                'type' => EntityTypeEnum::product,
                'category' => 'jr-mobile',
                'description' => 'Reliable mid-range smartphone with warranty support.',
                'order' => 10,
            ],
            [
                'name' => 'Flagship Display Phone',
                'type' => EntityTypeEnum::product,
                'category' => 'jr-mobile',
                'description' => 'Premium display and camera — ask in-store for current stock.',
                'order' => 11,
            ],
            [
                'name' => 'Fast Charger Bundle',
                'type' => EntityTypeEnum::product,
                'category' => 'jr-mobile',
                'description' => 'USB-C charger + cable pack for daily use.',
                'order' => 12,
            ],
            [
                'name' => '2BR Family Apartment',
                'type' => EntityTypeEnum::product,
                'category' => 'jr-real-estate',
                'description' => 'Sample listing — bright 2-bedroom flat in a quiet neighbourhood.',
                'order' => 20,
            ],
            [
                'name' => 'Corner Shop Space',
                'type' => EntityTypeEnum::product,
                'category' => 'jr-real-estate',
                'description' => 'Commercial rental listing for retail or café use.',
                'order' => 21,
            ],
            [
                'name' => 'Investment Plot',
                'type' => EntityTypeEnum::product,
                'category' => 'jr-real-estate',
                'description' => 'Land listing with clear documentation guidance.',
                'order' => 22,
            ],
            [
                'name' => 'Natural Wave Wig',
                'type' => EntityTypeEnum::product,
                'category' => 'ruties-hair',
                'description' => 'Soft natural-wave unit — ask for available lengths and colours in store.',
                'order' => 30,
            ],
            [
                'name' => 'Bone Straight Bundle',
                'type' => EntityTypeEnum::product,
                'category' => 'ruties-hair',
                'description' => 'Sleek straight hair bundles for sew-ins and installs.',
                'order' => 31,
            ],
            [
                'name' => 'Hair Care Starter Kit',
                'type' => EntityTypeEnum::product,
                'category' => 'ruties-hair',
                'description' => 'Shampoo, conditioner, and leave-in oil set for daily care.',
                'order' => 32,
            ],
            // Blog posts — hosted on this site
            [
                'name' => 'Why artificial grass works in Addis sun',
                'type' => EntityTypeEnum::post,
                'source' => PostSourceEnum::media,
                'category' => 'JR Ketema',
                'description' => 'Tips on pile height, UV resistance, and keeping turf clean through dusty seasons.',
                'order' => 1,
            ],
            [
                'name' => 'How to pick a phone that fits your budget',
                'type' => EntityTypeEnum::post,
                'source' => PostSourceEnum::media,
                'category' => 'JR Mobile',
                'description' => 'A simple checklist for battery, camera, and warranty before you buy.',
                'order' => 2,
            ],
            // Blog posts — social links (visitors are redirected to the original post)
            [
                'name' => 'Site walkthrough on TikTok',
                'type' => EntityTypeEnum::post,
                'source' => PostSourceEnum::social,
                'category' => 'JR Real Estate',
                'link' => 'https://www.tiktok.com/@jr',
                'description' => 'A short walkthrough of a listing — tap to watch on TikTok.',
                'order' => 3,
            ],
            [
                'name' => 'Hair look of the week on Instagram',
                'type' => EntityTypeEnum::post,
                'source' => PostSourceEnum::social,
                'category' => 'Ruties Hair',
                'link' => 'https://www.instagram.com/rutieshair',
                'description' => 'This week’s install and care tips — opens on Instagram.',
                'order' => 4,
            ],
            [
                'name' => 'JR updates on Telegram',
                'type' => EntityTypeEnum::post,
                'source' => PostSourceEnum::social,
                'category' => 'News',
                'link' => 'https://t.me/jr',
                'description' => 'Channel updates and in-store drops — opens in Telegram.',
                'order' => 5,
            ],
        ];

        foreach ($items as $item) {
            Entity::query()->create([
                ...$item,
                'status' => StatusEnum::active,
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);
        }
    }
}
