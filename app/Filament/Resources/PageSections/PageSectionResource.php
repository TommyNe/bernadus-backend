<?php

namespace App\Filament\Resources\PageSections;

use App\Filament\Resources\PageSections\Pages\CreatePageSection;
use App\Filament\Resources\PageSections\Pages\EditPageSection;
use App\Filament\Resources\PageSections\Pages\ListPageSections;
use App\Models\PageSection;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PageSectionResource extends Resource
{
    protected static ?string $model = PageSection::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;

    protected static ?string $modelLabel = 'Seitenabschnitt';

    protected static ?string $pluralModelLabel = 'Seitenabschnitte';

    protected static string|\UnitEnum|null $navigationGroup = 'Inhalte';

    protected static ?int $navigationSort = 20;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('page_id')
                    ->relationship('page', 'title')
                    ->label('Seite')
                    ->searchable()
                    ->preload()
                    ->required(),
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
                    ->required()
                    ->maxLength(255),
                TextInput::make('subtitle')
                    ->maxLength(255),
                Textarea::make('content')
                    ->rows(6)
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

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('page.title')
                    ->label('Seite')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('section_type')
                    ->label('Typ')
                    ->badge(),
                TextColumn::make('section_key')
                    ->label('Key')
                    ->placeholder('-')
                    ->toggleable(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->label('Sortierung')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
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
            'index' => ListPageSections::route('/'),
            'create' => CreatePageSection::route('/create'),
            'edit' => EditPageSection::route('/{record}/edit'),
        ];
    }
}
