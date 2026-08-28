<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            UserSeeder::class,
            OrganizationSeeder::class,
            MenuSeeder::class,
            NavbarMenuSeeder::class,
            PageSeeder::class,
            ServiceSeeder::class,
            ServiceMediaSeeder::class,
            HeroSeeder::class,
            TeamSeeder::class,
            EntitySeeder::class,
            TikTokCoverSeeder::class,
            PortfolioMediaSeeder::class,
            ProductReviewSeeder::class,
            StatsSeeder::class,
            AboutContentSeeder::class,
        ]);
    }
}
