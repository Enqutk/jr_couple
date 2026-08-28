<?php

namespace App\Http\Controllers;

use App\Enums\EntityTypeEnum;
use App\Enums\StatusEnum;
use App\Models\Entity;
use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::activeOrdered()->get();
        $category = $request->query('category');
        $search = trim((string) $request->query('q', ''));
        $feed = (string) $request->query('feed', 'all');

        if (! in_array($feed, ['all', 'new', 'trending'], true)) {
            $feed = 'all';
        }

        $baseQuery = fn (): Builder => Entity::query()
            ->where('status', StatusEnum::active)
            ->where('type', EntityTypeEnum::product)
            ->with('media');

        $featuredProduct = $baseQuery()
            ->where('is_featured', true)
            ->first();

        if (! $featuredProduct) {
            $featuredProduct = $baseQuery()->orderBy('order')->first();
        }

        $newProducts = $baseQuery()
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        $trendingProducts = $baseQuery()
            ->orderBy('order')
            ->take(5)
            ->get();

        $dealProducts = $baseQuery()
            ->orderBy('order')
            ->take(3)
            ->get();

        $productsQuery = $baseQuery()
            ->withAvg(['reviews' => fn ($query) => $query->where('status', StatusEnum::active)], 'rating')
            ->withCount(['reviews' => fn ($query) => $query->where('status', StatusEnum::active)]);

        if ($category) {
            $productsQuery->where('category', $category);
        }

        if ($search !== '') {
            $productsQuery->where(function (Builder $query) use ($search): void {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        match ($feed) {
            'new' => $productsQuery->orderByDesc('created_at'),
            'trending' => $productsQuery->orderBy('order')->orderByDesc('updated_at'),
            default => $productsQuery->orderBy('order'),
        };

        $products = $productsQuery->get();
        $activeService = $category
            ? $services->firstWhere('slug', $category)
            : null;

        return view('store.index', compact(
            'services',
            'products',
            'category',
            'activeService',
            'search',
            'feed',
            'featuredProduct',
            'newProducts',
            'trendingProducts',
            'dealProducts',
        ));
    }

    public function show(Entity $entity)
    {
        abort_unless(
            $entity->status === StatusEnum::active && $entity->type === EntityTypeEnum::product,
            404
        );

        $services = Service::activeOrdered()->get();
        $categoryLabel = $services->firstWhere('slug', $entity->category)?->title ?? $entity->category;

        $related = Entity::query()
            ->where('status', StatusEnum::active)
            ->where('type', EntityTypeEnum::product)
            ->where('category', $entity->category)
            ->where('id', '!=', $entity->id)
            ->with('media')
            ->withAvg(['reviews' => fn ($query) => $query->where('status', StatusEnum::active)], 'rating')
            ->withCount(['reviews' => fn ($query) => $query->where('status', StatusEnum::active)])
            ->orderBy('order')
            ->take(4)
            ->get();

        $reviews = $entity->activeReviews()->latest()->get();
        $averageRating = round((float) $reviews->avg('rating'), 1);
        $reviewCount = $reviews->count();

        return view('store.show', compact('entity', 'services', 'categoryLabel', 'related', 'reviews', 'averageRating', 'reviewCount'));
    }
}
