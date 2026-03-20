<?php

namespace App\Providers;

use App\Filament\Widgets\CurrentBoardWidget;
use App\Filament\Widgets\DashboardStatsWidget;
use App\Filament\Widgets\RecentChronicleEntriesWidget;
use App\Filament\Widgets\UpcomingEventsWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class BernadusAdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('Bernadus Admin')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                NavigationGroup::make('Inhalte')->icon('heroicon-o-document-text'),
                NavigationGroup::make('Verein')->icon('heroicon-o-users'),
                NavigationGroup::make('Veranstaltungen')->icon('heroicon-o-calendar-days'),
                NavigationGroup::make('Chronik')->icon('heroicon-o-book-open'),
                NavigationGroup::make('Wettbewerbe')->icon('heroicon-o-trophy'),
                NavigationGroup::make('Plakettenregeln')->icon('heroicon-o-adjustments-horizontal'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                DashboardStatsWidget::class,
                CurrentBoardWidget::class,
                UpcomingEventsWidget::class,
                RecentChronicleEntriesWidget::class,
                AccountWidget::class,
            ])
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
