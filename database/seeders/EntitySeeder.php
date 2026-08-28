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
                'description' => "30mm pile height for home gardens, balconies, and terraces.\n\nUV-resistant fibres that stay green through Addis sun and dust seasons. Includes drainage backing — ideal for patios where natural grass struggles.\n\nPrice is negotiable. Visit JR Ketema for samples and on-site measurement.",
                'price' => 12500,
                'price_label' => '/sqm',
                'is_negotiable' => true,
                'order' => 1,
            ],
            [
                'name' => 'Sports Pitch Turf 40mm',
                'type' => EntityTypeEnum::product,
                'category' => 'jr-ketema',
                'description' => "40mm sports-grade turf for futsal courts, school pitches, and training zones.\n\nBuilt for heavy foot traffic with shock-absorbing underlay options. We help with layout, line marking, and installation quotes.\n\nAsk in store for per-square-metre pricing.",
                'price' => 850,
                'price_label' => '/sqm',
                'is_negotiable' => true,
                'order' => 2,
            ],
            [
                'name' => 'Playground Soft Turf',
                'type' => EntityTypeEnum::product,
                'category' => 'jr-ketema',
                'description' => "Soft, child-friendly artificial grass for play areas and daycare centres.\n\nLower pile with cushioned feel underfoot. Easy to hose down after rainy days.\n\nFree consultation at our Ketema showroom.",
                'price' => 28000,
                'is_negotiable' => false,
                'order' => 3,
            ],
            [
                'name' => 'Android Everyday Phone',
                'type' => EntityTypeEnum::product,
                'category' => 'jr-mobile',
                'description' => "Reliable Android smartphone for daily calls, mobile money, and social apps.\n\n6.5\" display, dual SIM, and local warranty support through JR Mobile.\n\nStock rotates weekly — call ahead to confirm colour and storage options.",
                'price' => 18500,
                'is_negotiable' => true,
                'order' => 10,
            ],
            [
                'name' => 'Flagship Display Phone',
                'type' => EntityTypeEnum::product,
                'category' => 'jr-mobile',
                'description' => "Premium phone with bright OLED display and sharp camera for photos and video.\n\nIdeal for content creators and business users who need speed and battery life.\n\nFinancing and trade-in options may be available — ask in store.",
                'price' => 45000,
                'is_negotiable' => true,
                'order' => 11,
                'is_featured' => true,
            ],
            [
                'name' => 'Fast Charger Bundle',
                'type' => EntityTypeEnum::product,
                'category' => 'jr-mobile',
                'description' => "USB-C fast charger with braided cable — works with most current Android phones.\n\nCompact adapter for home, office, or travel. Genuine accessories only.\n\nBundle price is negotiable when bought with a handset.",
                'price' => 1200,
                'is_negotiable' => true,
                'order' => 12,
            ],
            [
                'name' => '2BR Family Apartment',
                'type' => EntityTypeEnum::product,
                'category' => 'jr-real-estate',
                'description' => "Bright two-bedroom apartment in a quiet neighbourhood with parking and security.\n\nOpen-plan living, modern kitchen, and balcony with city views.\n\nJR Real Estate handles viewings, lease terms, and documentation support.",
                'price' => 35000,
                'price_label' => '/month',
                'is_negotiable' => true,
                'order' => 20,
            ],
            [
                'name' => 'Corner Shop Space',
                'type' => EntityTypeEnum::product,
                'category' => 'jr-real-estate',
                'description' => "Ground-floor corner unit with street frontage — suited for retail, café, or salon.\n\nHigh foot traffic area with flexible lease lengths.\n\nContact us to schedule a site visit and discuss rent.",
                'price' => 25000,
                'price_label' => '/month',
                'is_negotiable' => true,
                'order' => 21,
            ],
            [
                'name' => 'Investment Plot',
                'type' => EntityTypeEnum::product,
                'category' => 'jr-real-estate',
                'description' => "Residential plot with clear title guidance and survey support.\n\nSuitable for family homes or small multi-unit builds.\n\nWe walk buyers through documentation and local permit basics.",
                'price' => 2500000,
                'is_negotiable' => true,
                'order' => 22,
            ],
            [
                'name' => 'Natural Wave Wig',
                'type' => EntityTypeEnum::product,
                'category' => 'ruties-hair',
                'description' => "Natural-wave lace-front unit with soft movement and realistic hairline.\n\nAvailable in multiple lengths and colours — try on at Ruties Hair.\n\nIncludes basic care tips and optional install booking.",
                'price' => 8500,
                'is_negotiable' => true,
                'order' => 30,
            ],
            [
                'name' => 'Bone Straight Bundle',
                'type' => EntityTypeEnum::product,
                'category' => 'ruties-hair',
                'description' => "Silky bone-straight bundles for sew-ins, quick weaves, and custom units.\n\n100% human hair quality tiers in stock — from everyday wear to premium grade.\n\nBundle deals when you buy three or more.",
                'price' => 6500,
                'is_negotiable' => true,
                'order' => 31,
            ],
            [
                'name' => 'Hair Care Starter Kit',
                'type' => EntityTypeEnum::product,
                'category' => 'ruties-hair',
                'description' => "Shampoo, deep conditioner, and leave-in oil set for wigs and natural hair.\n\nSulphate-free formulas that reduce dryness in dry climates.\n\nAsk staff to match products to your hair type.",
                'price' => 1850,
                'is_negotiable' => false,
                'order' => 32,
            ],
            // Blog posts — hosted on this site
            [
                'name' => 'Why artificial grass works in Addis sun',
                'type' => EntityTypeEnum::post,
                'source' => PostSourceEnum::media,
                'category' => 'JR Ketema',
                'description' => 'Tips on pile height, UV resistance, and keeping turf clean through dusty seasons.',
                'order' => 11,
            ],
            [
                'name' => 'How to pick a phone that fits your budget',
                'type' => EntityTypeEnum::post,
                'source' => PostSourceEnum::media,
                'category' => 'JR Mobile',
                'description' => 'A simple checklist for battery, camera, and warranty before you buy.',
                'order' => 12,
            ],
            [
                'name' => 'We are officially open',
                'type' => EntityTypeEnum::post,
                'source' => PostSourceEnum::social,
                'category' => 'TikTok',
                'link' => 'https://www.tiktok.com/@ruthandjonas/video/7635635118391971079',
                'description' => 'GOD did. We are officially open. Book your moments, let’s make them memorable.',
                'order' => 1,
            ],
            [
                'name' => 'Who’s team are you?',
                'type' => EntityTypeEnum::post,
                'source' => PostSourceEnum::social,
                'category' => 'TikTok',
                'link' => 'https://www.tiktok.com/@jr.m.o.b.i.l.e/video/7678649767488130322',
                'description' => 'Who’s team are you? Phones and deals from JR Mobile — tap to watch on TikTok.',
                'order' => 2,
            ],
            [
                'name' => 'Ruth’s Hair on TikTok',
                'type' => EntityTypeEnum::post,
                'source' => PostSourceEnum::social,
                'category' => 'TikTok',
                'link' => 'https://www.tiktok.com/@ruthishair',
                'description' => 'Installs and looks from Ruth’s Hair — follow on TikTok.',
                'order' => 3,
            ],
        ];

        foreach ($items as $item) {
            Entity::query()->create([
                ...$item,
                'is_featured' => $item['is_featured'] ?? false,
                'is_negotiable' => $item['is_negotiable'] ?? false,
                'price' => $item['price'] ?? null,
                'price_label' => $item['price_label'] ?? null,
                'status' => StatusEnum::active,
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);
        }
    }
}
