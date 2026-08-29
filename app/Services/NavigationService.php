<?php

namespace App\Services;

use App\Enums\MenuLocationEnum;
use App\Models\MenuItem;
use App\Models\MenuLocation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class NavigationService
{
    /** @var array<string, string> Menu path → route name / pattern */
    private const SECTION_ROUTES = [
        '/' => 'home',
        '/store' => 'store.*',
        '/blog' => 'blog.*',
        '/our-services' => 'services.*',
        '/contact' => 'contact',
        '/about' => 'about',
        '/portfolio' => 'portfolio.*',
    ];

    public function navbarItems(): Collection
    {
        $items = Cache::remember('nav.navbar', 60, function () {
            try {
                $location = MenuLocation::query()
                    ->where('location', MenuLocationEnum::Navbar)
                    ->first();
            } catch (\Throwable) {
                return $this->fallbackNavbar()->all();
            }

            if (! $location) {
                return $this->fallbackNavbar()->all();
            }

            try {
                $items = MenuItem::query()
                    ->where('menu_id', $location->id)
                    ->whereNull('parent_id')
                    ->orderBy('order_number')
                    ->with(['children' => fn ($q) => $q->orderBy('order_number')])
                    ->get();
            } catch (\Throwable) {
                return $this->fallbackNavbar()->all();
            }

            if ($items->isEmpty()) {
                return $this->fallbackNavbar()->all();
            }

            return $items->map(fn (MenuItem $item) => $this->mapItem($item))->all();
        });

        return $this->withActiveState(collect($items));
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

    private function withActiveState(Collection $items): Collection
    {
        $items = $items->map(function (array $item): array {
            $item['children'] = collect($item['children'] ?? [])
                ->map(function (array $child): array {
                    $child['active'] = $this->isActive((string) $child['url']);

                    return $child;
                })
                ->all();

            $item['active'] = $this->isActive((string) $item['url']);

            return $item;
        });

        $bestIndex = null;
        $bestLen = -1;

        foreach ($items as $index => $item) {
            if (empty($item['active'])) {
                continue;
            }

            $path = $this->pathOf((string) $item['url']);
            $len = strlen($path);

            if ($len > $bestLen) {
                $bestLen = $len;
                $bestIndex = $index;
            }
        }

        return $items->map(function (array $item, int $index) use ($bestIndex): array {
            $item['active'] = $index === $bestIndex;

            return $item;
        });
    }

    private function isActive(string $url): bool
    {
        $path = $this->pathOf($url);
        $current = $this->pathOf(request()->getPathInfo() ?: '/');

        if ($path === '/') {
            return $current === '/' && request()->routeIs('home');
        }

        if ($current === $path || str_starts_with($current, $path.'/')) {
            return true;
        }

        $routePattern = self::SECTION_ROUTES[$path] ?? null;

        return $routePattern !== null && request()->routeIs($routePattern);
    }

    private function pathOf(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH);

        if (! is_string($path) || $path === '') {
            $path = str_starts_with($url, 'http://') || str_starts_with($url, 'https://')
                ? '/'
                : $url;
        }

        $path = '/'.ltrim($path, '/');

        if ($path !== '/') {
            $path = rtrim($path, '/');
        }

        return $path === '' ? '/' : $path;
    }

    private function fallbackNavbar(): Collection
    {
        return collect([
            ['label' => 'Home', 'url' => url('/'), 'target' => '_self', 'children' => []],
            ['label' => 'Store', 'url' => url('/store'), 'target' => '_self', 'children' => []],
            ['label' => 'Blog', 'url' => url('/blog'), 'target' => '_self', 'children' => []],
            ['label' => 'Services', 'url' => url('/our-services'), 'target' => '_self', 'children' => []],
            ['label' => 'Contact', 'url' => url('/contact'), 'target' => '_self', 'children' => []],
        ]);
    }
}
