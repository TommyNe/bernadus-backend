<?php

namespace App\Filament\Resources\Chronicles;

use App\Filament\Resources\Chronicles\Pages\CreateChronicle;
use App\Filament\Resources\Chronicles\Pages\EditChronicle;
use App\Filament\Resources\Chronicles\Pages\ListChronicles;
use App\Filament\Resources\Chronicles\RelationManagers\EntriesRelationManager;
use App\Models\Chronicle;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ChronicleResource extends Resource
{
    protected static ?string $model = Chronicle::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?string $modelLabel = 'Chronik';

    protected static ?string $pluralModelLabel = 'Chroniken';

    protected static string|\UnitEnum|null $navigationGroup = 'Chronik';

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                TextInput::make('chronicle_key')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Textarea::make('description')
                    ->rows(4)
                    ->columnSpanFull(),
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
                TextColumn::make('chronicle_key')
                    ->label('Key')
                    ->searchable(),
                TextColumn::make('sort_order')
                    ->label('Sortierung')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
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
            EntriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListChronicles::route('/'),
            'create' => CreateChronicle::route('/create'),
            'edit' => EditChronicle::route('/{record}/edit'),
        ];
    }
}
