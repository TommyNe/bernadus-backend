<?php

namespace App\Filament\Resources\Flyers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FlyersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),
                TextColumn::make('title')
                    ->label('Titel')
                    ->placeholder('-')
                    ->searchable(),
                TextColumn::make('original_filename')
                    ->label('Datei')
                    ->searchable(),
                TextColumn::make('mime_type')
                    ->label('Typ')
                    ->badge(),
                TextColumn::make('file_size')
                    ->label('Größe')
                    ->numeric(),
                TextColumn::make('uploaded_at')
                    ->label('Hochgeladen')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('-')
                    ->sortable(),
            ])
            ->defaultSort('uploaded_at', 'desc')
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
