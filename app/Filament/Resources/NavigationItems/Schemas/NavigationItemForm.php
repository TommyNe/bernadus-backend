<?php

namespace App\Filament\Resources\NavigationItems\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NavigationItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Navigationseintrag')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('parent_id')
                                    ->label('Übergeordneter Eintrag')
                                    ->relationship(
                                        name: 'parent',
                                        titleAttribute: 'title',
                                        modifyQueryUsing: fn ($query) => $query->orderBy('sort_order')->orderBy('title'),
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Haupteintrag'),
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
                                    ->placeholder('/kontakt')
                                    ->rule('regex:/^\\/.*$/')
                                    ->unique(ignoreRecord: true),
                            ]),
                    ]),
                Section::make('Sichtbarkeit')
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
}
