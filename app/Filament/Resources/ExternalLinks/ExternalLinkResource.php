<?php

namespace App\Filament\Resources\ExternalLinks;

use App\Filament\Resources\ExternalLinks\Pages\CreateExternalLink;
use App\Filament\Resources\ExternalLinks\Pages\EditExternalLink;
use App\Filament\Resources\ExternalLinks\Pages\ListExternalLinks;
use App\Models\ExternalLink;
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

class ExternalLinkResource extends Resource
{
    protected static ?string $model = ExternalLink::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;

    protected static ?string $modelLabel = 'Externer Link';

    protected static ?string $pluralModelLabel = 'Externe Links';

    protected static string|\UnitEnum|null $navigationGroup = 'Inhalte';

    protected static ?int $navigationSort = 40;

    protected static ?string $recordTitleAttribute = 'label';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('link_key')
                    ->required()
                    ->maxLength(255),
                TextInput::make('label')
                    ->required()
                    ->maxLength(255),
                TextInput::make('url')
                    ->required()
                    ->url()
                    ->maxLength(255),
                Textarea::make('description')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->columns([
                TextColumn::make('label')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('link_key')
                    ->label('Key')
                    ->searchable(),
                TextColumn::make('url')
                    ->limit(40)
                    ->searchable(),
                TextColumn::make('updated_at')
                    ->label('Aktualisiert')
                    ->since()
                    ->sortable(),
            ])
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
            'index' => ListExternalLinks::route('/'),
            'create' => CreateExternalLink::route('/create'),
            'edit' => EditExternalLink::route('/{record}/edit'),
        ];
    }
}
