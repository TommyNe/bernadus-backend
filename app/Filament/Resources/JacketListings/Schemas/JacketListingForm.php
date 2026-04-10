<?php

namespace App\Filament\Resources\JacketListings\Schemas;

use App\Models\JacketListing;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class JacketListingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->label('Typ')
                    ->options(JacketListing::typeOptions())
                    ->required()
                    ->native(false),
                TextInput::make('title')
                    ->label('Titel')
                    ->required()
                    ->maxLength(255),
                Select::make('status')
                    ->label('Status')
                    ->options(JacketListing::statusOptions())
                    ->default('draft')
                    ->required()
                    ->native(false),
                TextInput::make('sort_order')
                    ->label('Sortierung')
                    ->numeric()
                    ->default(0)
                    ->required(),
                DateTimePicker::make('published_at')
                    ->label('Veröffentlicht am'),
                Textarea::make('details')
                    ->label('Details')
                    ->rows(6)
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('contact_hint')
                    ->label('Kontakthinweis')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }
}
