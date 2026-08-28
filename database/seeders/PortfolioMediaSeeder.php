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
            public_path('assets/images/majiworks/maji-project-highland.png'),
            public_path('assets/images/majiworks/maji-service-gis.png'),
            public_path('assets/images/majiworks/maji-about-field.png'),
            public_path('assets/images/majiworks/maji-service-wash.png'),
            public_path('assets/images/majiworks/maji-hero-irrigation.png'),
            public_path('assets/images/majiworks/maji-service-solar.png'),
        ];

        $products = Entity::query()
            ->where('type', EntityTypeEnum::product)
            ->orderBy('order')
            ->get();

        foreach ($products as $index => $product) {
            $path = $images[$index % count($images)];

            if (! is_file($path)) {
                continue;
            }

            $product->clearMediaCollection('image');
            $product->addMedia($path)
                ->preservingOriginal()
                ->toMediaCollection('image');
        }
    }
}
