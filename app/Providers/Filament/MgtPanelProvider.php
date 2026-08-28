<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\QuickLinksWidget;
use App\Filament\Widgets\StoreStatsWidget;
use App\Models\Organization;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class MgtPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $accent = Organization::defaultTheme()['accent'];
        $brandName = 'JR Couple';

        try {
            $organization = Organization::query()->first();
            if ($organization) {
                $brandName = $organization->title ?: $brandName;
                $theme = $organization->resolvedTheme();
                $accent = $theme['accent'] ?? $accent;
            }
        } catch (\Throwable) {
            // DB may not be ready during early boot / migrate.
        }

        return $panel
            ->default()
            ->id('mgt')
            ->path('mgt')
            ->login()
            ->brandName($brandName.' Admin')
            ->brandLogo(fn () => new HtmlString(view('filament.brand', ['name' => $brandName])->render()))
            ->brandLogoHeight('2rem')
            ->colors([
                'primary' => Color::hex($accent),
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Store')
                    ->icon('heroicon-o-shopping-bag')
                    ->collapsed(false),
                NavigationGroup::make()
                    ->label('Website')
                    ->icon('heroicon-o-globe-alt')
                    ->collapsed(false),
                NavigationGroup::make()
                    ->label('Settings')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsed(false),
                NavigationGroup::make()
                    ->label('Menus')
                    ->icon('heroicon-o-bars-3')
                    ->collapsed(true),
                NavigationGroup::make()
                    ->label('Content')
                    ->icon('heroicon-o-document-text')
                    ->collapsed(true),
                NavigationGroup::make()
                    ->label('Other')
                    ->collapsed(true),
            ])
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                StoreStatsWidget::class,
                QuickLinksWidget::class,
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn () => Blade::render('@include(\'filament.hooks.admin-styles\')')
            )
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
