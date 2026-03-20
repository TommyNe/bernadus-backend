<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\CurrentBoardWidget;
use App\Filament\Widgets\QuickLinksWidget;
use App\Filament\Widgets\RecentChronicleEntriesWidget;
use App\Filament\Widgets\UpcomingEventsWidget;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class ContentOverviewPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-eye';

    protected static string|\UnitEnum|null $navigationGroup = 'Inhalte';

    protected static ?string $navigationLabel = 'Inhaltsübersicht';

    protected static ?int $navigationSort = 5;

    protected static ?string $title = 'Inhaltsübersicht';

    protected static string $routePath = 'content-overview';

    protected string $view = 'filament-panels::pages.page';

    public function getTitle(): string|Htmlable
    {
        return 'Inhaltsübersicht';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            QuickLinksWidget::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return [
            'md' => 1,
            'xl' => 1,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            CurrentBoardWidget::class,
            UpcomingEventsWidget::class,
            RecentChronicleEntriesWidget::class,
        ];
    }

    public function getFooterWidgetsColumns(): int|array
    {
        return [
            'md' => 2,
            'xl' => 3,
        ];
    }
}
