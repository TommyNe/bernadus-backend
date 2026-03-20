<?php

namespace App\Filament\Resources\CompetitionResults;

use App\Filament\Resources\CompetitionResults\Pages\CreateCompetitionResult;
use App\Filament\Resources\CompetitionResults\Pages\EditCompetitionResult;
use App\Filament\Resources\CompetitionResults\Pages\ListCompetitionResults;
use App\Models\CompetitionResult;
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

class CompetitionResultResource extends Resource
{
    protected static ?string $model = CompetitionResult::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedListBullet;

    protected static ?string $modelLabel = 'Ergebnis';

    protected static ?string $pluralModelLabel = 'Ergebnisse';

    protected static string|\UnitEnum|null $navigationGroup = 'Wettbewerbe';

    protected static ?int $navigationSort = 40;

    protected static ?string $recordTitleAttribute = 'winner_name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('competition_result_category_id')
                    ->relationship('category', 'name')
                    ->label('Ergebnisgruppe')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('person_id')
                    ->relationship('person', 'display_name')
                    ->label('Person')
                    ->searchable()
                    ->preload(),
                TextInput::make('winner_name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('rank')
                    ->label('Platz')
                    ->required()
                    ->numeric(),
                TextInput::make('score')
                    ->label('Punktzahl')
                    ->numeric(),
                TextInput::make('score_text')
                    ->label('Punkttext')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('winner_name')
            ->columns([
                TextColumn::make('category.competition.title')
                    ->label('Wettbewerb')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('Gruppe')
                    ->searchable(),
                TextColumn::make('rank')
                    ->label('Platz')
                    ->sortable(),
                TextColumn::make('winner_name')
                    ->label('Gewinner')
                    ->searchable(),
                TextColumn::make('person.display_name')
                    ->label('Verknüpfte Person')
                    ->placeholder('-'),
            ])
            ->defaultSort('rank')
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
            'index' => ListCompetitionResults::route('/'),
            'create' => CreateCompetitionResult::route('/create'),
            'edit' => EditCompetitionResult::route('/{record}/edit'),
        ];
    }
}
