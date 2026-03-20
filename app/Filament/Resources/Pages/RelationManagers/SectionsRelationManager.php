<?php

namespace App\Filament\Resources\Pages\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SectionsRelationManager extends RelationManager
{
    protected static string $relationship = 'sections';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('section_key')
                    ->label('Section-Key')
                    ->maxLength(255),
                Select::make('section_type')
                    ->label('Abschnittstyp')
                    ->options([
                        'hero' => 'Hero',
                        'rich_text' => 'Rich Text',
                        'notice' => 'Hinweis',
                        'faq' => 'FAQ',
                        'cards' => 'Karten',
                        'cta' => 'Call to Action',
                    ])
                    ->native(false)
                    ->required(),
                TextInput::make('title')
                    ->maxLength(255),
                TextInput::make('subtitle')
                    ->maxLength(255),
                Textarea::make('content')
                    ->rows(4)
                    ->columnSpanFull(),
                KeyValue::make('data')
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->label('Sortierung')
                    ->numeric()
                    ->default(0)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('section_type')
                    ->label('Typ')
                    ->badge(),
                TextColumn::make('section_key')
                    ->label('Key')
                    ->placeholder('-'),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->label('Sortierung')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
