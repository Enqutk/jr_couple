<?php

namespace App\Services;

use App\Enums\MenuLocationEnum;
use App\Models\MenuItem;
use App\Models\MenuLocation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class NavigationService
{
    public function navbarItems(): Collection
    {
        return Cache::remember('nav.navbar', 60, function () {
            $location = MenuLocation::query()
                ->where('location', MenuLocationEnum::Navbar)
                ->first();

            if (! $location) {
                return $this->fallbackNavbar();
            }

            $items = MenuItem::query()
                ->where('menu_id', $location->id)
                ->whereNull('parent_id')
                ->orderBy('order_number')
                ->with(['children' => fn ($q) => $q->orderBy('order_number')])
                ->get();

            if ($items->isEmpty()) {
                return $this->fallbackNavbar();
            }

            return $items->map(fn (MenuItem $item) => $this->mapItem($item));
        });
    }

    public function clearCache(): void
    {
        Cache::forget('nav.navbar');
    }

    private function mapItem(MenuItem $item): array
    {
        $url = $this->normalizeUrl((string) $item->url);

        return [
            'label' => $item->title,
            'url' => $url,
            'target' => $item->target ?: '_self',
            'active' => $this->isActive($url),
            'children' => $item->children
                ->map(fn (MenuItem $child) => $this->mapItem($child))
                ->values()
                ->all(),
        ];
    }

    private function normalizeUrl(string $url): string
    {
        $url = trim($url);

        if ($url === '' || $url === '#') {
            return url('/');
        }

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://') || str_starts_with($url, 'mailto:')) {
            return $url;
        }

        // Legacy / incorrect seeded paths → real routes
        $aliases = [
            '/home' => '/',
            '/service' => '/our-services',
            '/services' => '/our-services',
            '/project' => '/portfolio',
            '/projects' => '/portfolio',
            '/about/history' => '/about',
            '/about/team' => '/about#team',
        ];

        $path = '/'.ltrim(parse_url($url, PHP_URL_PATH) ?: $url, '/');
        $fragment = parse_url($url, PHP_URL_FRAGMENT);
        $mapped = $aliases[$path] ?? $path;

        if ($fragment && ! str_contains($mapped, '#')) {
            $mapped .= '#'.$fragment;
        }

        return url($mapped);
    }

    private function isActive(string $url): bool
    {
        $path = parse_url($url, PHP_URL_PATH) ?: '/';
        $current = '/'.ltrim(request()->getPathInfo(), '/');
        if ($current === '//') {
            $current = '/';
        }

        if ($path === '/' || $path === '') {
            return $current === '/';
        }

        return $current === $path || str_starts_with($current, rtrim($path, '/').'/');
    }

    private function fallbackNavbar(): Collection
    {
        return collect([
            ['label' => 'Home', 'url' => url('/'), 'target' => '_self', 'active' => request()->routeIs('home'), 'children' => []],
            ['label' => 'Store', 'url' => url('/store'), 'target' => '_self', 'active' => request()->routeIs('store.*'), 'children' => []],
            ['label' => 'Blog', 'url' => url('/blog'), 'target' => '_self', 'active' => request()->routeIs('blog.*'), 'children' => []],
            ['label' => 'Services', 'url' => url('/our-services'), 'target' => '_self', 'active' => request()->routeIs('services.*'), 'children' => []],
            ['label' => 'Contact', 'url' => url('/contact'), 'target' => '_self', 'active' => request()->routeIs('contact'), 'children' => []],
        ]);
    }
}
