<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use App\Models\Hero;
use App\Models\User;
use Illuminate\Database\Seeder;

class HeroSeeder extends Seeder
{
    public function run(): void
    {
        $userId = User::query()->value('id');
        $base = public_path('assets/images/jr');
        Hero::query()->forceDelete();

        $slides = [
            [
                'title' => 'Four brands. One JR family.',
                'subtitle' => 'Grass · Mobile · Real estate · Hair',
                'description' => 'Shop JR Ketema, JR Mobile, JR Real Estate, and Ruties Hair — or visit our store for products by category.',
                'button_link' => '/store',
                'text_link' => 'Browse the store',
                'order' => 1,
                'image' => $base.'/hero-slide-1.png',
            ],
            [
                'title' => 'Artificial grass that lasts',
                'subtitle' => 'JR Ketema',
                'description' => 'Turf for homes, play areas, and sports — less water, more green.',
                'button_link' => '/services/jr-ketema',
                'text_link' => 'Explore JR Ketema',
                'order' => 2,
                'image' => $base.'/hero-slide-2.png',
            ],
            [
                'title' => 'Phones, property & hair',
                'subtitle' => 'JR Mobile · Real Estate · Ruties Hair',
                'description' => 'Trusted handsets, clear property moves, and hair looks that turn heads.',
                'button_link' => '/our-services',
                'text_link' => 'See all services',
                'order' => 3,
                'image' => $base.'/hero-slide-3.png',
            ],
        ];

        foreach ($slides as $slide) {
            $imagePath = $slide['image'] ?? null;
            unset($slide['image']);

            $hero = Hero::query()->create([
                ...$slide,
                'status' => StatusEnum::active,
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);

            if ($imagePath && is_file($imagePath)) {
                $hero->addMedia($imagePath)
                    ->preservingOriginal()
                    ->toMediaCollection('image');
            }
        }
    }
}
