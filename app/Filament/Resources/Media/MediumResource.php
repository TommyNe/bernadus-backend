<?php

namespace App\Filament\Resources\Media;

use App\Filament\Resources\Media\Pages\CreateMedium;
use App\Filament\Resources\Media\Pages\EditMedium;
use App\Filament\Resources\Media\Pages\ListMedia;
use App\Models\Medium;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MediumResource extends Resource
{
    protected static ?string $model = Medium::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static ?string $modelLabel = 'Medium';

    protected static ?string $pluralModelLabel = 'Medien';

    protected static string|\UnitEnum|null $navigationGroup = 'Inhalte';

    protected static ?string $navigationLabel = 'Medien';

    protected static ?int $navigationSort = 30;

    protected static ?string $recordTitleAttribute = 'filename';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->maxLength(255),
                TextInput::make('disk')
                    ->default('public')
                    ->required()
                    ->maxLength(255),
                TextInput::make('path')
                    ->required()
                    ->maxLength(255),
                TextInput::make('filename')
                    ->required()
                    ->maxLength(255),
                TextInput::make('mime_type')
                    ->required()
                    ->maxLength(255),
                TextInput::make('extension')
                    ->maxLength(50),
                TextInput::make('size')
                    ->numeric(),
                TextInput::make('width')
                    ->numeric(),
                TextInput::make('height')
                    ->numeric(),
                Textarea::make('alt_text')
                    ->label('Alternativtext')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('filename')
            ->columns([
                ImageColumn::make('path')
                    ->label('Vorschau')
                    ->disk('public')
                    ->square(),
                TextColumn::make('title')
                    ->label('Titel')
                    ->placeholder('-')
                    ->searchable(),
                TextColumn::make('filename')
                    ->label('Datei')
                    ->searchable(),
                TextColumn::make('mime_type')
                    ->label('Typ')
                    ->badge(),
                TextColumn::make('size')
                    ->label('Größe')
                    ->numeric(),
                TextColumn::make('updated_at')
                    ->label('Aktualisiert')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
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
            'index' => ListMedia::route('/'),
            'create' => CreateMedium::route('/create'),
            'edit' => EditMedium::route('/{record}/edit'),
        ];
    }
}
