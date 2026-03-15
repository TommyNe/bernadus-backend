<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

abstract class ContentResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Redaktion';

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Inhalt')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('title')
                                    ->label('Titel')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                                TextInput::make('path')
                                    ->label('Pfad')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('/beispiel')
                                    ->rule('regex:/^\\/.*$/'),
                                Textarea::make('summary')
                                    ->label('Kurzbeschreibung')
                                    ->rows(3)
                                    ->maxLength(255),
                            ]),
                        RichEditor::make('content')
                            ->label('Inhalt')
                            ->columnSpanFull()
                            ->fileAttachmentsVisibility('private'),
                    ]),
                Section::make('Veroeffentlichung')
                    ->schema([
                        TextInput::make('sort_order')
                            ->label('Sortierung')
                            ->numeric()
                            ->required()
                            ->default(0)
                            ->minValue(0),
                        Toggle::make('is_active')
                            ->label('Aktiv')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Eintrag')
                    ->schema([
                        TextEntry::make('title')
                            ->label('Titel'),
                        TextEntry::make('slug')
                            ->label('Slug')
                            ->badge(),
                        TextEntry::make('path')
                            ->label('Pfad'),
                        IconEntry::make('is_active')
                            ->label('Aktiv')
                            ->boolean(),
                        TextEntry::make('sort_order')
                            ->label('Sortierung')
                            ->numeric(),
                        TextEntry::make('summary')
                            ->label('Kurzbeschreibung')
                            ->columnSpanFull()
                            ->placeholder('-'),
                        TextEntry::make('content')
                            ->label('Inhalt')
                            ->html()
                            ->columnSpanFull()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->label('Aktualisiert')
                            ->dateTime('d.m.Y H:i'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('title')
                    ->label('Titel')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('path')
                    ->label('Pfad')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sort_order')
                    ->label('Sortierung')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Aktualisiert')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Aktiv'),
            ])
            ->recordActions([
                \Filament\Actions\ViewAction::make(),
                \Filament\Actions\EditAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->orderBy('sort_order')
            ->orderBy('title');
    }
}
