<?php

namespace App\Filament\Resources\Chronicles\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EntriesRelationManager extends RelationManager
{
    protected static string $relationship = 'entries';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('year')
                    ->label('Jahr')
                    ->required()
                    ->numeric(),
                TextInput::make('title')
                    ->maxLength(255),
                Textarea::make('pair_text')
                    ->label('Eintrag')
                    ->required()
                    ->rows(2)
                    ->columnSpanFull(),
                TextInput::make('headline')
                    ->maxLength(255),
                Textarea::make('secondary_text')
                    ->label('Zusatztext')
                    ->rows(2)
                    ->columnSpanFull(),
                Select::make('image_media_id')
                    ->relationship('image', 'filename')
                    ->label('Bild')
                    ->searchable()
                    ->preload(),
                Toggle::make('is_highlighted')
                    ->label('Highlight')
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
            ->recordTitleAttribute('pair_text')
            ->columns([
                TextColumn::make('year')
                    ->label('Jahr')
                    ->sortable(),
                TextColumn::make('pair_text')
                    ->label('Eintrag')
                    ->limit(40)
                    ->searchable(),
                IconColumn::make('is_highlighted')
                    ->label('Highlight')
                    ->boolean(),
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
