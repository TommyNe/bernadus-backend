<?php

namespace App\Filament\Resources\Pages\RelationManagers;

use App\Filament\PageSections\PageSectionForm;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SectionsRelationManager extends RelationManager
{
    protected static string $relationship = 'sections';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components(PageSectionForm::components());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('section_type')
                    ->label('Typ')
                    ->badge(),
                TextColumn::make('section_key')
                    ->label('Key')
                    ->placeholder('-'),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
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
