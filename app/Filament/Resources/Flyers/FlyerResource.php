<?php

namespace App\Filament\Resources\Flyers;

use App\Filament\Resources\Flyers\Pages\CreateFlyer;
use App\Filament\Resources\Flyers\Pages\EditFlyer;
use App\Filament\Resources\Flyers\Pages\ListFlyers;
use App\Filament\Resources\Flyers\Schemas\FlyerForm;
use App\Filament\Resources\Flyers\Tables\FlyersTable;
use App\Models\Flyer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FlyerResource extends Resource
{
    protected static ?string $model = Flyer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $modelLabel = 'Flyer';

    protected static ?string $pluralModelLabel = 'Flyer';

    protected static string|\UnitEnum|null $navigationGroup = 'Inhalte';

    protected static ?string $navigationLabel = 'Flyer';

    protected static ?int $navigationSort = 35;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return FlyerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FlyersTable::configure($table);
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
            'index' => ListFlyers::route('/'),
            'create' => CreateFlyer::route('/create'),
            'edit' => EditFlyer::route('/{record}/edit'),
        ];
    }
}
