<?php

namespace App\Filament\Resources\People\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RoleAssignmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'roleAssignments';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('role_id')
                    ->relationship('role', 'name')
                    ->label('Amt')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('label_override')
                    ->label('Angezeigter Text')
                    ->maxLength(255),
                DatePicker::make('started_on')
                    ->label('Beginn'),
                DatePicker::make('ended_on')
                    ->label('Ende'),
                Toggle::make('is_current')
                    ->label('Aktuell')
                    ->default(true),
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
            ->recordTitleAttribute('label_override')
            ->columns([
                TextColumn::make('role.name')
                    ->label('Amt')
                    ->searchable(),
                TextColumn::make('label_override')
                    ->label('Angezeigter Text')
                    ->placeholder('-'),
                IconColumn::make('is_current')
                    ->label('Aktuell')
                    ->boolean(),
                TextColumn::make('started_on')
                    ->label('Beginn')
                    ->date('d.m.Y')
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
