<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceMediaSeeder extends Seeder
{
    public function run(): void
    {
        $base = public_path('assets/images/jr/store');
        $covers = public_path('assets/images/jr/covers');

        $bySlug = [
            'jr-ketema' => [
                'main' => $covers.'/ketema.jpg',
                'secondary' => $base.'/sports-pitch-turf.jpg',
            ],
            'jr-mobile' => [
                'main' => $covers.'/mobile-1.jpg',
                'secondary' => $base.'/android-phone.jpg',
            ],
            'jr-real-estate' => [
                'main' => $covers.'/real-estate.jpg',
                'secondary' => $base.'/investment-plot.jpg',
            ],
            'ruties-hair' => [
                'main' => $covers.'/hair-avatar.jpg',
                'secondary' => $base.'/hair-care-kit.jpg',
            ],
        ];

        foreach ($bySlug as $slug => $images) {
            $service = Service::query()->where('slug', $slug)->first();

            if (! $service) {
                continue;
            }

            $service->clearMediaCollection('main_image');
            $service->clearMediaCollection('secondary_image');

            if (is_file($images['main'])) {
                $service->addMedia($images['main'])
                    ->preservingOriginal()
                    ->toMediaCollection('main_image');
            }

            if (is_file($images['secondary'])) {
                $service->addMedia($images['secondary'])
                    ->preservingOriginal()
                    ->toMediaCollection('secondary_image');
            }
        }
    }
}
