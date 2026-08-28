<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\MenuLocation;
use App\Services\NavigationService;
use Illuminate\Database\Seeder;

class NavbarMenuSeeder extends Seeder
{
    public function run(): void
    {
        $location = MenuLocation::query()->firstOrCreate(
            ['slug' => 'main-menu'],
            [
                'name' => 'Main Menu',
                'location' => 'navbar',
                'description' => 'Primary public navigation',
            ]
        );

        MenuItem::query()->where('menu_id', $location->id)->forceDelete();

        $items = [
            ['title' => 'Home', 'url' => '/', 'order_number' => 1],
            ['title' => 'Store', 'url' => '/store', 'order_number' => 2],
            ['title' => 'Blog', 'url' => '/blog', 'order_number' => 3],
            ['title' => 'Services', 'url' => '/our-services', 'order_number' => 4],
            ['title' => 'Contact', 'url' => '/contact', 'order_number' => 5],
        ];

        foreach ($items as $item) {
            MenuItem::query()->create([
                'menu_id' => $location->id,
                'parent_id' => null,
                'title' => $item['title'],
                'link_type' => 'internal',
                'url' => $item['url'],
                'target' => '_self',
                'order_number' => $item['order_number'],
            ]);
        }

        app(NavigationService::class)->clearCache();
    }
}
