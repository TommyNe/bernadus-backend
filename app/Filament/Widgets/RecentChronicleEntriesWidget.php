<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ChronicleEntries\ChronicleEntryResource;
use App\Models\ChronicleEntry;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentChronicleEntriesWidget extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => ChronicleEntry::query()
                ->with('chronicle')
                ->latest('updated_at'))
            ->columns([
                TextColumn::make('chronicle.title')
                    ->label('Chronik')
                    ->searchable(),
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
                TextColumn::make('updated_at')
                    ->label('Aktualisiert')
                    ->since(),
            ])
            ->defaultPaginationPageOption(5)
            ->recordActions([
                EditAction::make()
                    ->url(fn (ChronicleEntry $record): string => ChronicleEntryResource::getUrl('edit', ['record' => $record])),
            ])
            ->toolbarActions([]);
    }
}
