<?php

namespace App\Filament\Widgets;

use App\Models\ExternalLink;
use App\Models\Page;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class QuickLinksWidget extends StatsOverviewWidget
{
    protected ?string $heading = 'Direktzugriffe';

    protected ?string $description = 'Schnelle Sprünge zu zentralen Vereinslinks.';

    protected function getStats(): array
    {
        $links = ExternalLink::query()
            ->whereIn('link_key', [
                'official_flyer',
                'official_contact',
                'official_whatsapp',
            ])
            ->get()
            ->keyBy('link_key');

        $aboutPage = Page::query()->where('slug', 'ueber-uns')->first();

        return [
            Stat::make('Über uns', $aboutPage?->title ?? 'Nicht angelegt')
                ->description($aboutPage !== null ? 'Seite direkt bearbeiten' : 'Seite fehlt noch')
                ->color('primary')
                ->icon('heroicon-o-document-text')
                ->url($aboutPage !== null ? route('filament.admin.resources.pages.edit', ['record' => $aboutPage]) : route('filament.admin.resources.pages.index')),
            Stat::make('Flyer', 'Direkt öffnen')
                ->description($links->get('official_flyer')?->label ?? 'Nicht hinterlegt')
                ->color('warning')
                ->icon('heroicon-o-document')
                ->url($links->get('official_flyer')?->url),
            Stat::make('Kontakt', 'Direkt öffnen')
                ->description($links->get('official_contact')?->label ?? 'Nicht hinterlegt')
                ->color('info')
                ->icon('heroicon-o-envelope')
                ->url($links->get('official_contact')?->url),
            Stat::make('WhatsApp', 'Direkt öffnen')
                ->description($links->get('official_whatsapp')?->label ?? 'Nicht hinterlegt')
                ->color('success')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->url($links->get('official_whatsapp')?->url),
        ];
    }
}
