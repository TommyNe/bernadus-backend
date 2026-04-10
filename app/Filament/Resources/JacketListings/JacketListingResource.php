<?php

namespace App\Filament\Resources\JacketListings;

use App\Filament\Resources\JacketListings\Pages\CreateJacketListing;
use App\Filament\Resources\JacketListings\Pages\EditJacketListing;
use App\Filament\Resources\JacketListings\Pages\ListJacketListings;
use App\Filament\Resources\JacketListings\Schemas\JacketListingForm;
use App\Filament\Resources\JacketListings\Tables\JacketListingsTable;
use App\Models\JacketListing;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class JacketListingResource extends Resource
{
    protected static ?string $model = JacketListing::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    protected static ?string $modelLabel = 'Eintrag';

    protected static ?string $pluralModelLabel = 'Jackenbörse';

    protected static string|\UnitEnum|null $navigationGroup = 'Inhalte';

    protected static ?string $navigationLabel = 'Jackenbörse';

    protected static ?int $navigationSort = 45;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return JacketListingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JacketListingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJacketListings::route('/'),
            'create' => CreateJacketListing::route('/create'),
            'edit' => EditJacketListing::route('/{record}/edit'),
        ];
    }
}
