<?php

namespace App\Filament\Resources\CompetitionResultCategories;

use App\Filament\Resources\CompetitionResultCategories\Pages\CreateCompetitionResultCategory;
use App\Filament\Resources\CompetitionResultCategories\Pages\EditCompetitionResultCategory;
use App\Filament\Resources\CompetitionResultCategories\Pages\ListCompetitionResultCategories;
use App\Filament\Resources\CompetitionResultCategories\RelationManagers\ResultsRelationManager;
use App\Models\CompetitionResultCategory;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CompetitionResultCategoryResource extends Resource
{
    protected static ?string $model = CompetitionResultCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?string $modelLabel = 'Ergebnisgruppe';

    protected static ?string $pluralModelLabel = 'Ergebnisgruppen';

    protected static string|\UnitEnum|null $navigationGroup = 'Wettbewerbe';

    protected static ?int $navigationSort = 30;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('competition_id')
                    ->relationship('competition', 'title')
                    ->label('Wettbewerb')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('sort_order')
                    ->label('Sortierung')
                    ->numeric()
                    ->default(0)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('competition.title')
                    ->label('Wettbewerb')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->label('Sortierung')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
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
            ResultsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCompetitionResultCategories::route('/'),
            'create' => CreateCompetitionResultCategory::route('/create'),
            'edit' => EditCompetitionResultCategory::route('/{record}/edit'),
        ];
    }
}
