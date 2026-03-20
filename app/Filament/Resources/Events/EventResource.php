<?php

namespace App\Filament\Resources\Events;

use App\Filament\Resources\Events\Pages\CreateEvent;
use App\Filament\Resources\Events\Pages\EditEvent;
use App\Filament\Resources\Events\Pages\ListEvents;
use App\Models\Event;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $modelLabel = 'Termin';

    protected static ?string $pluralModelLabel = 'Termine';

    protected static string|\UnitEnum|null $navigationGroup = 'Veranstaltungen';

    protected static ?int $navigationSort = 20;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Select::make('venue_id')
                    ->relationship('venue', 'name')
                    ->label('Ort')
                    ->searchable()
                    ->preload(),
                Textarea::make('description')
                    ->rows(5)
                    ->columnSpanFull(),
                DateTimePicker::make('starts_at')
                    ->label('Beginn'),
                DateTimePicker::make('ends_at')
                    ->label('Ende'),
                Toggle::make('all_day')
                    ->label('Ganztägig'),
                TextInput::make('display_date_text')
                    ->label('Anzeigedatum')
                    ->maxLength(255),
                TextInput::make('month_label')
                    ->label('Monat')
                    ->maxLength(255),
                TextInput::make('audience_text')
                    ->label('Zielgruppe')
                    ->maxLength(255),
                TextInput::make('source_url')
                    ->label('Quell-URL')
                    ->url()
                    ->maxLength(255),
                TextInput::make('external_ics_url')
                    ->label('ICS-URL')
                    ->url()
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
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('month_label')
                    ->label('Monat')
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('starts_at')
                    ->label('Beginn')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('venue.name')
                    ->label('Ort')
                    ->placeholder('-'),
                TextColumn::make('audience_text')
                    ->label('Zielgruppe')
                    ->placeholder('-'),
                TextColumn::make('sort_order')
                    ->label('Sortierung')
                    ->sortable(),
                IconColumn::make('all_day')
                    ->label('Ganztägig')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('month_label')
                    ->label('Monat')
                    ->options(fn (): array => Event::query()
                        ->whereNotNull('month_label')
                        ->orderBy('month_label')
                        ->pluck('month_label', 'month_label')
                        ->all()),
                SelectFilter::make('venue_id')
                    ->label('Ort')
                    ->relationship('venue', 'name'),
            ])
            ->defaultSort('starts_at')
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
            'index' => ListEvents::route('/'),
            'create' => CreateEvent::route('/create'),
            'edit' => EditEvent::route('/{record}/edit'),
        ];
    }
}
