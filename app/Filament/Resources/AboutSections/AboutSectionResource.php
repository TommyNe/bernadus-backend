<?php

namespace App\Filament\Resources\AboutSections;

use App\Filament\Resources\ContentResource;
use App\Filament\Resources\AboutSections\Pages\CreateAboutSection;
use App\Filament\Resources\AboutSections\Pages\EditAboutSection;
use App\Filament\Resources\AboutSections\Pages\ListAboutSections;
use App\Filament\Resources\AboutSections\Pages\ViewAboutSection;
use App\Models\AboutSection;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class AboutSectionResource extends ContentResource
{
    protected static ?string $model = AboutSection::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInformationCircle;

    protected static ?string $navigationLabel = 'Über uns';

    protected static ?int $navigationSort = 20;

    protected static ?string $modelLabel = 'Bereich';

    protected static ?string $pluralModelLabel = 'Bereiche';

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
            'index' => ListAboutSections::route('/'),
            'create' => CreateAboutSection::route('/create'),
            'view' => ViewAboutSection::route('/{record}'),
            'edit' => EditAboutSection::route('/{record}/edit'),
        ];
    }
}
