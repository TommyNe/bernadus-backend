<?php

namespace App\Filament\Resources\CompetitionTypes;

use App\Filament\Resources\CompetitionTypes\Pages\CreateCompetitionType;
use App\Filament\Resources\CompetitionTypes\Pages\EditCompetitionType;
use App\Filament\Resources\CompetitionTypes\Pages\ListCompetitionTypes;
use App\Models\CompetitionType;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CompetitionTypeResource extends Resource
{
    protected static ?string $model = CompetitionType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $modelLabel = 'Wettbewerbstyp';

    protected static ?string $pluralModelLabel = 'Wettbewerbstypen';

    protected static string|\UnitEnum|null $navigationGroup = 'Wettbewerbe';

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('type_key')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type_key')
                    ->label('Key')
                    ->searchable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => ListCompetitionTypes::route('/'),
            'create' => CreateCompetitionType::route('/create'),
            'edit' => EditCompetitionType::route('/{record}/edit'),
        ];
    }
}
