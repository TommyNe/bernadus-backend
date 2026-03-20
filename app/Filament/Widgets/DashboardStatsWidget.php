<?php

namespace App\Filament\Widgets;

use App\Models\ChronicleEntry;
use App\Models\Event;
use App\Models\Page;
use App\Models\RoleAssignment;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsWidget extends StatsOverviewWidget
{
    protected ?string $heading = 'Überblick';

    protected ?string $description = 'Wichtige Kennzahlen für die redaktionelle Pflege.';

    protected function getStats(): array
    {
        return [
            Stat::make('Veröffentlichte Seiten', Page::query()->where('status', 'published')->count())
                ->description('Aktuell sichtbare Seiten')
                ->color('success')
                ->icon('heroicon-o-document-text'),
            Stat::make('Aktuelle Funktionäre', RoleAssignment::query()->where('is_current', true)->count())
                ->description('Laufende Amtszuweisungen')
                ->color('warning')
                ->icon('heroicon-o-users'),
            Stat::make('Termine im laufenden Jahr', Event::query()->whereYear('starts_at', now()->year)->count())
                ->description((string) now()->year)
                ->color('info')
                ->icon('heroicon-o-calendar-days'),
            Stat::make('Letzte Chronikeinträge', ChronicleEntry::query()->count())
                ->description('Einträge über alle Chroniken')
                ->color('gray')
                ->icon('heroicon-o-book-open'),
        ];
    }
}
