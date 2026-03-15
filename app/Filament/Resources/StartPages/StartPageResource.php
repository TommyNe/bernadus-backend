<?php

namespace App\Filament\Resources\StartPages;

use App\Filament\Resources\ContentResource;
use App\Filament\Resources\StartPages\Pages\CreateStartPage;
use App\Filament\Resources\StartPages\Pages\EditStartPage;
use App\Filament\Resources\StartPages\Pages\ListStartPages;
use App\Filament\Resources\StartPages\Pages\ViewStartPage;
use App\Models\StartPage;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class StartPageResource extends ContentResource
{
    protected static ?string $model = StartPage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?string $navigationLabel = 'Startseite';

    protected static ?int $navigationSort = 10;

    protected static ?string $modelLabel = 'Startseiteneintrag';

    protected static ?string $pluralModelLabel = 'Startseiteneinträge';

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
            'index' => ListStartPages::route('/'),
            'create' => CreateStartPage::route('/create'),
            'view' => ViewStartPage::route('/{record}'),
            'edit' => EditStartPage::route('/{record}/edit'),
        ];
    }
}
