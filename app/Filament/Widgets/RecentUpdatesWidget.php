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
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;

class RecentUpdatesWidget extends Widget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected string $view = 'filament.widgets.recent-updates-widget';

    /**
     * @return Collection<int, array{label: string, title: string, path: string, updated_at: string, timestamp: int, is_active: bool, icon: string}>
     */
    public function getItems(): Collection
    {
        return collect([
            ['label' => 'Startseite', 'model' => StartPage::class, 'icon' => Heroicon::OutlinedHome],
            ['label' => 'Über uns', 'model' => AboutSection::class, 'icon' => Heroicon::OutlinedInformationCircle],
            ['label' => 'Service & Material', 'model' => ServiceMaterial::class, 'icon' => Heroicon::OutlinedWrenchScrewdriver],
            ['label' => 'Mitmachen', 'model' => ParticipationOption::class, 'icon' => Heroicon::OutlinedUserGroup],
            ['label' => 'Termine & Events', 'model' => EventItem::class, 'icon' => Heroicon::OutlinedCalendarDays],
            ['label' => 'Kontakt', 'model' => ContactEntry::class, 'icon' => Heroicon::OutlinedEnvelope],
            ['label' => 'Galerie & Ehrungen', 'model' => GalleryHonor::class, 'icon' => Heroicon::OutlinedPhoto],
            ['label' => 'Navigation', 'model' => NavigationItem::class, 'icon' => Heroicon::OutlinedBars3BottomLeft],
        ])
            ->flatMap(function (array $definition): Collection {
                return $definition['model']::query()
                    ->latest('updated_at')
                    ->limit(2)
                    ->get(['title', 'path', 'updated_at', 'is_active'])
                    ->map(fn ($record): array => [
                        'label' => $definition['label'],
                        'title' => $record->title,
                        'path' => $record->path,
                        'updated_at' => $record->updated_at->format('d.m.Y H:i'),
                        'timestamp' => $record->updated_at->getTimestamp(),
                        'is_active' => (bool) $record->is_active,
                        'icon' => $definition['icon'],
                    ]);
            })
            ->sortByDesc('timestamp')
            ->take(8)
            ->values();
    }
}
