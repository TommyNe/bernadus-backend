<?php

namespace App\Filament\Resources\ParticipationOptions;

use App\Filament\Resources\ContentResource;
use App\Filament\Resources\ParticipationOptions\Pages\CreateParticipationOption;
use App\Filament\Resources\ParticipationOptions\Pages\EditParticipationOption;
use App\Filament\Resources\ParticipationOptions\Pages\ListParticipationOptions;
use App\Filament\Resources\ParticipationOptions\Pages\ViewParticipationOption;
use App\Models\ParticipationOption;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class ParticipationOptionResource extends ContentResource
{
    protected static ?string $model = ParticipationOption::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Mitmachen';

    protected static ?int $navigationSort = 40;

    protected static ?string $modelLabel = 'Mitmachoption';

    protected static ?string $pluralModelLabel = 'Mitmachoptionen';

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
            'index' => ListParticipationOptions::route('/'),
            'create' => CreateParticipationOption::route('/create'),
            'view' => ViewParticipationOption::route('/{record}'),
            'edit' => EditParticipationOption::route('/{record}/edit'),
        ];
    }
}
