<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Insert menu location
        $menuLocationId = DB::table('menu_locations')->insertGetId([
            'name' => 'Main Menu',
            'slug' => 'main-menu',
            'location' => 'navbar',
            'description' => 'Default main navigation menu',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Insert top-level menu items
        $homeId = DB::table('menu_items')->insertGetId([
            'menu_id' => $menuLocationId,
            'parent_id' => null,
            'title' => 'Home',
            'icon' => null,
            'link_type' => 'internal',
            'url' => '/',
            'target' => '_self',
            'order_number' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $aboutId = DB::table('menu_items')->insertGetId([
            'menu_id' => $menuLocationId,
            'parent_id' => null,
            'title' => 'About',
            'icon' => null,
            'link_type' => 'internal',
            'url' => '/about',
            'target' => '_self',
            'order_number' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Submenus under About
        DB::table('menu_items')->insert([
            [
                'menu_id' => $menuLocationId,
                'parent_id' => $aboutId,
                'title' => 'Our History',
                'icon' => null,
                'link_type' => 'internal',
                'url' => '/about/history',
                'target' => '_self',
                'order_number' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'menu_id' => $menuLocationId,
                'parent_id' => $aboutId,
                'title' => 'Our Team',
                'icon' => null,
                'link_type' => 'internal',
                'url' => '/about/team',
                'target' => '_self',
                'order_number' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        DB::table('menu_items')->insert([
            [
                'menu_id' => $menuLocationId,
                'parent_id' => null,
                'title' => 'Service',
                'icon' => null,
                'link_type' => 'internal',
                'url' => '/our-services',
                'target' => '_self',
                'order_number' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'menu_id' => $menuLocationId,
                'parent_id' => null,
                'title' => 'Portfolio',
                'icon' => null,
                'link_type' => 'internal',
                'url' => '/portfolio',
                'target' => '_self',
                'order_number' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'menu_id' => $menuLocationId,
                'parent_id' => null,
                'title' => 'Contact Us',
                'icon' => null,
                'link_type' => 'internal',
                'url' => '/contact',
                'target' => '_self',
                'order_number' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
