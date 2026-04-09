<?php

namespace App\Filament\Resources\Competitions;

use App\Filament\Resources\Competitions\Pages\CreateCompetition;
use App\Filament\Resources\Competitions\Pages\EditCompetition;
use App\Filament\Resources\Competitions\Pages\ListCompetitions;
use App\Filament\Resources\Competitions\RelationManagers\MilestoneAwardsRelationManager;
use App\Filament\Resources\Competitions\RelationManagers\PlaqueAwardRulesRelationManager;
use App\Filament\Resources\Competitions\RelationManagers\ResultCategoriesRelationManager;
use App\Filament\Resources\Competitions\RelationManagers\ScoreAwardsRelationManager;
use App\Models\Competition;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Validation\Rules\Unique;

class CompetitionResource extends Resource
{
    protected static ?string $model = Competition::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;

    protected static ?string $modelLabel = 'Wettbewerb';

    protected static ?string $pluralModelLabel = 'Wettbewerbe';

    protected static string|\UnitEnum|null $navigationGroup = 'Wettbewerbe';

    protected static ?int $navigationSort = 20;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('competition_type_id')
                    ->relationship('type', 'name')
                    ->label('Wettbewerbstyp')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required(),
                Select::make('event_id')
                    ->relationship('event', 'title')
                    ->label('Termin')
                    ->searchable()
                    ->preload(),
                TextInput::make('year')
                    ->label('Jahr')
                    ->numeric()
                    ->unique(
                        ignoreRecord: true,
                        modifyRuleUsing: function (Unique $rule, Get $get): Unique {
                            return $rule->where('competition_type_id', $get('competition_type_id'));
                        }
                    ),
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->rows(5)
                    ->columnSpanFull(),
                TextInput::make('source_url')
                    ->label('Quell-URL')
                    ->url()
                    ->maxLength(255),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Entwurf',
                        'published' => 'Veröffentlicht',
                    ])
                    ->required()
                    ->native(false),
                DateTimePicker::make('published_at')
                    ->label('Veröffentlicht am'),
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
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type.name')
                    ->label('Typ')
                    ->searchable(),
                TextColumn::make('year')
                    ->label('Jahr')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'published' ? 'Veröffentlicht' : 'Entwurf')
                    ->color(fn (string $state): string => $state === 'published' ? 'success' : 'gray'),
                TextColumn::make('published_at')
                    ->label('Veröffentlicht')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('event.title')
                    ->label('Termin')
                    ->placeholder('-'),
                TextColumn::make('sort_order')
                    ->label('Sortierung')
                    ->sortable(),
            ])
            ->defaultSort('year', 'desc')
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
            ResultCategoriesRelationManager::class,
            PlaqueAwardRulesRelationManager::class,
            MilestoneAwardsRelationManager::class,
            ScoreAwardsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCompetitions::route('/'),
            'create' => CreateCompetition::route('/create'),
            'edit' => EditCompetition::route('/{record}/edit'),
        ];
    }
}
