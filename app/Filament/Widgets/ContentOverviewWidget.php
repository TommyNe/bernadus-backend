<?php

namespace App\Filament\Widgets;

use App\Models\AboutSection;
use App\Models\ContactEntry;
use App\Models\EventItem;
use App\Models\GalleryHonor;
use App\Models\NavigationItem;
use App\Models\ParticipationOption;
use App\Models\ServiceMaterial;
use App\Models\StartPage;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContentOverviewWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'Inhaltsübersicht';

    protected ?string $description = 'Redaktionelle Kennzahlen für Inhalte und Navigation.';

    /**
     * @return array<Stat>
     */
    protected function getStats(): array
    {
        $contentModels = [
            StartPage::class,
            AboutSection::class,
            ServiceMaterial::class,
            ParticipationOption::class,
            EventItem::class,
            ContactEntry::class,
            GalleryHonor::class,
        ];

        $contentEntries = collect($contentModels)
            ->sum(fn (string $model): int => $model::query()->count());

        $activeContentEntries = collect($contentModels)
            ->sum(fn (string $model): int => $model::query()->where('is_active', true)->count());

        $inactiveEntries = collect($contentModels)
            ->sum(fn (string $model): int => $model::query()->where('is_active', false)->count())
            + NavigationItem::query()->where('is_active', false)->count();

        return [
            Stat::make('Inhalte im System', $contentEntries)
                ->description('Alle redaktionellen Einträge')
                ->descriptionIcon(Heroicon::OutlinedDocumentText)
                ->color('primary')
                ->chart([2, 3, 3, 4, 5, 6, 7]),
            Stat::make('Aktive Inhalte', $activeContentEntries)
                ->description('Derzeit öffentlich geschaltet')
                ->descriptionIcon(Heroicon::OutlinedCheckBadge)
                ->color('success')
                ->chart([1, 2, 3, 4, 4, 5, 6]),
            Stat::make('Navigationseinträge', NavigationItem::query()->count())
                ->description('Punkte in Haupt- und Unternavigation')
                ->descriptionIcon(Heroicon::OutlinedBars3BottomLeft)
                ->color('info')
                ->chart([1, 1, 2, 2, 3, 3, 4]),
            Stat::make('Zu prüfen', $inactiveEntries)
                ->description('Inaktive Inhalte oder Menüpunkte')
                ->descriptionIcon(Heroicon::OutlinedExclamationTriangle)
                ->color($inactiveEntries > 0 ? 'warning' : 'gray')
                ->chart([4, 3, 3, 2, 2, 1, 1]),
        ];
    }
}
