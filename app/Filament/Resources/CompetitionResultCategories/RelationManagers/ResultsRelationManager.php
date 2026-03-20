<?php

namespace App\Filament\Resources\CompetitionResultCategories\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ResultsRelationManager extends RelationManager
{
    protected static string $relationship = 'results';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('winner_name')
            ->columns([
                TextColumn::make('winner_name')
                    ->searchable(),
                TextColumn::make('rank')
                    ->label('Platz')
                    ->sortable(),
                TextColumn::make('person.display_name')
                    ->label('Person')
                    ->placeholder('-'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
