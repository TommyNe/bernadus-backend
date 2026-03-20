<?php

namespace App\Filament\Resources\Competitions\RelationManagers;

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

class PlaqueAwardRulesRelationManager extends RelationManager
{
    protected static string $relationship = 'plaqueAwardRules';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('rule_type')
                    ->label('Regeltyp')
                    ->options([
                        'ring_threshold' => 'Ring-Schwelle',
                        'gold_milestone' => 'Gold-Meilenstein',
                    ])
                    ->native(false)
                    ->required(),
                TextInput::make('age_from')
                    ->label('Alter von')
                    ->numeric(),
                TextInput::make('age_to')
                    ->label('Alter bis')
                    ->numeric(),
                TextInput::make('required_score')
                    ->label('Benötigte Punktzahl')
                    ->numeric(),
                TextInput::make('required_gold_count')
                    ->label('Benötigte Goldzahl')
                    ->numeric(),
                TextInput::make('award_name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('award_level')
                    ->label('Stufe')
                    ->maxLength(255),
                TextInput::make('sort_order')
                    ->label('Sortierung')
                    ->numeric()
                    ->default(0)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('award_name')
            ->columns([
                TextColumn::make('award_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rule_type')
                    ->label('Typ')
                    ->badge(),
                TextColumn::make('sort_order')
                    ->label('Sortierung')
                    ->sortable(),
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
