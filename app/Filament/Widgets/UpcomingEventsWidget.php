<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Events\EventResource;
use App\Models\Event;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class UpcomingEventsWidget extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Event::query()
                ->with('venue')
                ->where(function (Builder $query): void {
                    $query
                        ->whereNull('starts_at')
                        ->orWhere('starts_at', '>=', now()->startOfDay());
                })
                ->orderBy('starts_at')
                ->orderBy('sort_order'))
            ->columns([
                TextColumn::make('title')
                    ->label('Termin')
                    ->searchable(),
                TextColumn::make('starts_at')
                    ->label('Beginn')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('Offen'),
                TextColumn::make('venue.name')
                    ->label('Ort')
                    ->placeholder('-'),
                TextColumn::make('audience_text')
                    ->label('Zielgruppe')
                    ->placeholder('-'),
                IconColumn::make('all_day')
                    ->label('Ganztägig')
                    ->boolean(),
            ])
            ->defaultPaginationPageOption(5)
            ->recordActions([
                EditAction::make()
                    ->url(fn (Event $record): string => EventResource::getUrl('edit', ['record' => $record])),
            ])
            ->toolbarActions([]);
    }
}
