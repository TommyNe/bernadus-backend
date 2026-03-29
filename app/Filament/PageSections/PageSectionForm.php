<?php

namespace App\Filament\PageSections;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;

class PageSectionForm
{
    /**
     * @return array<int, mixed>
     */
    public static function components(bool $withPageSelect = false): array
    {
        $components = [];

        if ($withPageSelect) {
            $components[] = Select::make('page_id')
                ->relationship('page', 'title')
                ->label('Seite')
                ->searchable()
                ->preload()
                ->required();
        }

        $components[] = TextInput::make('section_key')
            ->label('Section-Key')
            ->helperText('Empfohlene Keys für "Über uns": hero-about, about-overview, history-meaning, about-highlights.')
            ->maxLength(255);

        $components[] = Select::make('section_type')
            ->label('Abschnittstyp')
            ->options([
                'hero' => 'Hero',
                'rich_text' => 'Rich Text',
                'notice' => 'Hinweis',
                'faq' => 'FAQ',
                'cards' => 'Karten',
                'cta' => 'Call to Action',
            ])
            ->live()
            ->native(false)
            ->required();

        $components[] = TextInput::make('title')
            ->required()
            ->maxLength(255);

        $components[] = TextInput::make('subtitle')
            ->maxLength(255);

        $components[] = Textarea::make('content')
            ->rows(6)
            ->columnSpanFull();

        $components[] = Repeater::make('data.items')
            ->label('Einträge')
            ->schema([
                TextInput::make('title')
                    ->label('Titel')
                    ->maxLength(255),
                Textarea::make('content')
                    ->label('Inhalt')
                    ->rows(3)
                    ->columnSpanFull(),
                TextInput::make('icon')
                    ->label('Icon')
                    ->maxLength(255),
                TextInput::make('link_label')
                    ->label('Link-Label')
                    ->maxLength(255),
                TextInput::make('link_url')
                    ->label('Link-URL')
                    ->url()
                    ->maxLength(255),
            ])
            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
            ->visible(fn (Get $get): bool => in_array($get('section_type'), ['cards', 'faq'], true))
            ->columnSpanFull();

        $components[] = TextInput::make('data.button_label')
            ->label('Button-Label')
            ->maxLength(255)
            ->visible(fn (Get $get): bool => $get('section_type') === 'cta');

        $components[] = TextInput::make('data.button_url')
            ->label('Button-URL')
            ->url()
            ->maxLength(255)
            ->visible(fn (Get $get): bool => $get('section_type') === 'cta');

        $components[] = Select::make('data.tone')
            ->label('Hinweis-Stil')
            ->options([
                'info' => 'Info',
                'success' => 'Erfolg',
                'warning' => 'Warnung',
            ])
            ->native(false)
            ->visible(fn (Get $get): bool => $get('section_type') === 'notice');

        $components[] = KeyValue::make('data.meta')
            ->label('Zusatzdaten')
            ->helperText('Für weitere optionale Werte, die vom Frontend ausgewertet werden.')
            ->columnSpanFull();

        $components[] = TextInput::make('sort_order')
            ->label('Sortierung')
            ->numeric()
            ->default(0)
            ->required();

        return $components;
    }
}
