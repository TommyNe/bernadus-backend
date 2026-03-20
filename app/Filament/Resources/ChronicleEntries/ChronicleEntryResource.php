<?php

namespace App\Filament\Resources\ChronicleEntries;

use App\Filament\Resources\ChronicleEntries\Pages\CreateChronicleEntry;
use App\Filament\Resources\ChronicleEntries\Pages\EditChronicleEntry;
use App\Filament\Resources\ChronicleEntries\Pages\ListChronicleEntries;
use App\Models\ChronicleEntry;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ChronicleEntryResource extends Resource
{
    protected static ?string $model = ChronicleEntry::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookmarkSquare;

    protected static ?string $modelLabel = 'Chronikeintrag';

    protected static ?string $pluralModelLabel = 'Chronikeinträge';

    protected static string|\UnitEnum|null $navigationGroup = 'Chronik';

    protected static ?int $navigationSort = 20;

    protected static ?string $recordTitleAttribute = 'pair_text';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('chronicle_id')
                    ->relationship('chronicle', 'title')
                    ->label('Chronik')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('year')
                    ->label('Jahr')
                    ->required()
                    ->numeric(),
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                TextInput::make('headline')
                    ->maxLength(255),
                Textarea::make('pair_text')
                    ->label('Paar / Eintrag')
                    ->required()
                    ->rows(2)
                    ->columnSpanFull(),
                Textarea::make('secondary_text')
                    ->label('Zusatztext')
                    ->rows(2)
                    ->columnSpanFull(),
                Select::make('image_media_id')
                    ->relationship('image', 'filename')
                    ->label('Bild')
                    ->searchable()
                    ->preload(),
                TextInput::make('external_image_url')
                    ->label('Externe Bild-URL')
                    ->url()
                    ->maxLength(255),
                TextInput::make('source_url')
                    ->label('Quell-URL')
                    ->url()
                    ->maxLength(255),
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

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('pair_text')
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
                TextColumn::make('sort_order')
                    ->label('Sortierung')
                    ->sortable(),
            ])
            ->defaultSort('year', 'desc')
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
            'index' => ListChronicleEntries::route('/'),
            'create' => CreateChronicleEntry::route('/create'),
            'edit' => EditChronicleEntry::route('/{record}/edit'),
        ];
    }
}
