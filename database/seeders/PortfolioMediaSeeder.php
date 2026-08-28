<?php

namespace Database\Seeders;

use App\Enums\EntityTypeEnum;
use App\Models\Entity;
use Illuminate\Database\Seeder;

class PortfolioMediaSeeder extends Seeder
{
    public function run(): void
    {
        $images = [
            'Home Lawn Turf 30mm' => 'home-lawn-turf.jpg',
            'Sports Pitch Turf 40mm' => 'sports-pitch-turf.jpg',
            'Playground Soft Turf' => 'playground-turf.jpg',
            'Android Everyday Phone' => 'android-phone.jpg',
            'Flagship Display Phone' => 'flagship-phone.jpg',
            'Fast Charger Bundle' => 'charger-bundle.jpg',
            '2BR Family Apartment' => 'apartment-2br.jpg',
            'Corner Shop Space' => 'corner-shop.jpg',
            'Investment Plot' => 'investment-plot.jpg',
            'Natural Wave Wig' => 'wave-wig.jpg',
            'Bone Straight Bundle' => 'straight-bundle.jpg',
            'Hair Care Starter Kit' => 'hair-care-kit.jpg',
        ];

        $basePath = public_path('assets/images/jr/store');

        Entity::query()
            ->where('type', EntityTypeEnum::product)
            ->orderBy('order')
            ->get()
            ->each(function (Entity $product) use ($images, $basePath): void {
                $file = $images[$product->name] ?? null;

                if (! $file) {
                    return;
                }

                $path = $basePath.'/'.$file;

                if (! is_file($path)) {
                    return;
                }

                $product->clearMediaCollection('image');
                $product->addMedia($path)
                    ->preservingOriginal()
                    ->toMediaCollection('image');
            });
    }
}
