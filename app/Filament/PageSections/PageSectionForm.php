<?php

namespace App\Filament\PageSections;

use App\Models\ExternalLink;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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
            ->helperText('Empfohlene Keys für "Über uns": hero-about, about-overview, history-meaning, about-highlights. Für "Mitglied werden": membership-hero, membership-offers, practical-note-1, practical-note-2, membership-faq. Für "Kontakt": contact-hero, contact-intro, contact-options, contact-address, contact-source-note, contact-official-links.')
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

        $components[] = TextInput::make('data.eyebrow')
            ->label('Eyebrow')
            ->helperText('Optionaler kurzer Vorlauf über der Hero-Überschrift.')
            ->maxLength(255)
            ->visible(fn (Get $get): bool => $get('section_type') === 'hero');

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
                Select::make('link_key')
                    ->label('Externer Link')
                    ->helperText('Optional: zentral gepflegten Link auswählen.')
                    ->options(fn (): array => ExternalLink::query()->orderBy('label')->pluck('label', 'link_key')->all())
                    ->searchable()
                    ->preload()
                    ->native(false),
                TextInput::make('link_label')
                    ->label('Link-Label')
                    ->maxLength(255),
                TextInput::make('link_url')
                    ->label('Link-URL')
                    ->url()
                    ->helperText('Nur nutzen, wenn kein zentraler Link-Key verwendet wird.')
                    ->maxLength(255),
                TextInput::make('sort_order')
                    ->label('Sortierung')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Toggle::make('is_active')
                    ->label('Aktiv')
                    ->default(true),
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

        $components[] = Repeater::make('data.notes')
            ->label('Hinweise')
            ->schema([
                Textarea::make('content')
                    ->label('Hinweis')
                    ->rows(3)
                    ->required()
                    ->columnSpanFull(),
            ])
            ->itemLabel(fn (array $state): ?string => $state['content'] ?? null)
            ->visible(fn (Get $get): bool => $get('section_type') === 'notice')
            ->columnSpanFull();

        $components[] = TextInput::make('data.name')
            ->label('Adressname')
            ->maxLength(255)
            ->visible(fn (Get $get): bool => $get('section_key') === 'contact-address');

        $components[] = TextInput::make('data.street')
            ->label('Straße')
            ->maxLength(255)
            ->visible(fn (Get $get): bool => $get('section_key') === 'contact-address');

        $components[] = TextInput::make('data.postal_code')
            ->label('PLZ')
            ->maxLength(32)
            ->visible(fn (Get $get): bool => $get('section_key') === 'contact-address');

        $components[] = TextInput::make('data.city')
            ->label('Ort')
            ->maxLength(255)
            ->visible(fn (Get $get): bool => $get('section_key') === 'contact-address');

        $components[] = TextInput::make('data.country')
            ->label('Land')
            ->maxLength(255)
            ->visible(fn (Get $get): bool => $get('section_key') === 'contact-address');

        $components[] = Textarea::make('data.address_notes')
            ->label('Adresshinweis')
            ->rows(3)
            ->visible(fn (Get $get): bool => $get('section_key') === 'contact-address')
            ->columnSpanFull();

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
