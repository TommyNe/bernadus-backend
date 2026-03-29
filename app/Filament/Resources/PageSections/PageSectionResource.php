<?php

namespace App\Filament\Resources\PageSections;

use App\Filament\PageSections\PageSectionForm;
use App\Filament\Resources\PageSections\Pages\CreatePageSection;
use App\Filament\Resources\PageSections\Pages\EditPageSection;
use App\Filament\Resources\PageSections\Pages\ListPageSections;
use App\Models\PageSection;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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
            ->components(PageSectionForm::components(withPageSelect: true));
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
