<?php

namespace App\Filament\Resources\NavigationItems\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NavigationItemInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Eintrag')
                    ->schema([
                        TextEntry::make('title')
                            ->label('Titel'),
                        TextEntry::make('parent.title')
                            ->label('Übergeordnet')
                            ->placeholder('Haupteintrag'),
                        TextEntry::make('slug')
                            ->label('Slug')
                            ->badge(),
                        TextEntry::make('path')
                            ->label('Pfad'),
                        TextEntry::make('sort_order')
                            ->label('Sortierung')
                            ->numeric(),
                        IconEntry::make('is_active')
                            ->label('Aktiv')
                            ->boolean(),
                        TextEntry::make('updated_at')
                            ->label('Aktualisiert')
                            ->dateTime('d.m.Y H:i'),
                    ])
                    ->columns(2),
            ]);
    }
}
