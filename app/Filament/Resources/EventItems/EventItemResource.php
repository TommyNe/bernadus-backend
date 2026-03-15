<?php

namespace App\Filament\Resources\EventItems;

use App\Filament\Resources\ContentResource;
use App\Filament\Resources\EventItems\Pages\CreateEventItem;
use App\Filament\Resources\EventItems\Pages\EditEventItem;
use App\Filament\Resources\EventItems\Pages\ListEventItems;
use App\Filament\Resources\EventItems\Pages\ViewEventItem;
use App\Models\EventItem;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class EventItemResource extends ContentResource
{
    protected static ?string $model = EventItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $navigationLabel = 'Termine & Events';

    protected static ?int $navigationSort = 50;

    protected static ?string $modelLabel = 'Termin';

    protected static ?string $pluralModelLabel = 'Termine';

    protected static ?string $recordTitleAttribute = 'title';

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEventItems::route('/'),
            'create' => CreateEventItem::route('/create'),
            'view' => ViewEventItem::route('/{record}'),
            'edit' => EditEventItem::route('/{record}/edit'),
        ];
    }
}
