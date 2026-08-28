<?php

namespace App\Filament\Widgets;

use App\Enums\EntityTypeEnum;
use App\Enums\StatusEnum;
use App\Models\Entity;
use App\Models\ProductReview;
use App\Models\Service;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StoreStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $products = Entity::query()
            ->where('type', EntityTypeEnum::product)
            ->where('status', StatusEnum::active)
            ->count();

        $featured = Entity::query()
            ->where('type', EntityTypeEnum::product)
            ->where('is_featured', true)
            ->count();

        $reviews = ProductReview::query()->count();

        $services = Service::query()
            ->where('status', StatusEnum::active)
            ->count();

        return [
            Stat::make('Store products', (string) $products)
                ->description('Active listings')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary')
                ->url(route('filament.mgt.resources.entities.index')),
            Stat::make('JR brands', (string) $services)
                ->description('Service pages')
                ->descriptionIcon('heroicon-m-squares-2x2')
                ->color('success')
                ->url(route('filament.mgt.resources.services.index')),
            Stat::make('Product reviews', (string) $reviews)
                ->description('Customer feedback')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('warning')
                ->url(route('filament.mgt.resources.product-reviews.index')),
            Stat::make('Store hero', $featured > 0 ? 'Promoted' : 'None set')
                ->description('Featured product on /store')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color($featured > 0 ? 'success' : 'gray'),
        ];
    }
}
