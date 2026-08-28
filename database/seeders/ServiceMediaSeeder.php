<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceMediaSeeder extends Seeder
{
    public function run(): void
    {
        $base = public_path('assets/images/majiworks');

        $bySlug = [
            'jr-ketema' => $base.'/maji-project-highland.png',
            'jr-mobile' => $base.'/maji-service-gis.png',
            'jr-real-estate' => $base.'/maji-about-field.png',
            'ruties-hair' => $base.'/maji-service-governance.png',
        ];

        foreach ($bySlug as $slug => $path) {
            $service = Service::query()->where('slug', $slug)->first();

            if (! $service || ! is_file($path)) {
                continue;
            }

            $service->clearMediaCollection('main_image');
            $service->clearMediaCollection('secondary_image');

            $service->addMedia($path)
                ->preservingOriginal()
                ->toMediaCollection('main_image');

            $service->addMedia($path)
                ->preservingOriginal()
                ->toMediaCollection('secondary_image');
        }
    }
}
