<?php

namespace App\Filament\Resources\GalleryImages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class GalleryImageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Bildtitel')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('image_path')
                    ->label('Bilddatei')
                    ->image()
                    ->disk('public')
                    ->directory('gallery')
                    ->required()
                    ->openable()
                    ->downloadable()
                    ->imageEditor()
                    ->columnSpanFull(),
                Textarea::make('alt_text')
                    ->label('Alternativtext')
                    ->rows(2)
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->label('Sortierung')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->label('Aktiv')
                    ->default(true),
            ]);
    }
}
