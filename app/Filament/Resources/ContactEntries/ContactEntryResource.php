<?php

namespace App\Filament\Resources\ContactEntries;

use App\Filament\Resources\ContentResource;
use App\Filament\Resources\ContactEntries\Pages\CreateContactEntry;
use App\Filament\Resources\ContactEntries\Pages\EditContactEntry;
use App\Filament\Resources\ContactEntries\Pages\ListContactEntries;
use App\Filament\Resources\ContactEntries\Pages\ViewContactEntry;
use App\Models\ContactEntry;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class ContactEntryResource extends ContentResource
{
    protected static ?string $model = ContactEntry::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static ?string $navigationLabel = 'Kontakt';

    protected static ?int $navigationSort = 60;

    protected static ?string $modelLabel = 'Kontakteintrag';

    protected static ?string $pluralModelLabel = 'Kontakteinträge';

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
            'index' => ListContactEntries::route('/'),
            'create' => CreateContactEntry::route('/create'),
            'view' => ViewContactEntry::route('/{record}'),
            'edit' => EditContactEntry::route('/{record}/edit'),
        ];
    }
}
