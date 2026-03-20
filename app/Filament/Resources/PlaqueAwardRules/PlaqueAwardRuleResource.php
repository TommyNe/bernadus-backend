<?php

namespace App\Filament\Resources\PlaqueAwardRules;

use App\Filament\Resources\PlaqueAwardRules\Pages\CreatePlaqueAwardRule;
use App\Filament\Resources\PlaqueAwardRules\Pages\EditPlaqueAwardRule;
use App\Filament\Resources\PlaqueAwardRules\Pages\ListPlaqueAwardRules;
use App\Models\PlaqueAwardRule;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PlaqueAwardRuleResource extends Resource
{
    protected static ?string $model = PlaqueAwardRule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    protected static ?string $modelLabel = 'Plakettenregel';

    protected static ?string $pluralModelLabel = 'Plakettenregeln';

    protected static string|\UnitEnum|null $navigationGroup = 'Plakettenregeln';

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'award_name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('competition_id')
                    ->relationship('competition', 'title')
                    ->label('Wettbewerb')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('rule_type')
                    ->label('Regeltyp')
                    ->options([
                        'ring_threshold' => 'Ring-Schwelle',
                        'gold_milestone' => 'Gold-Meilenstein',
                    ])
                    ->native(false)
                    ->required(),
                TextInput::make('age_from')
                    ->label('Alter von')
                    ->numeric(),
                TextInput::make('age_to')
                    ->label('Alter bis')
                    ->numeric(),
                TextInput::make('required_score')
                    ->label('Benötigte Punktzahl')
                    ->numeric(),
                TextInput::make('required_gold_count')
                    ->label('Benötigte Goldzahl')
                    ->numeric(),
                TextInput::make('award_name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('award_level')
                    ->label('Stufe')
                    ->maxLength(255),
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
            ->recordTitleAttribute('award_name')
            ->columns([
                TextColumn::make('competition.title')
                    ->label('Wettbewerb')
                    ->searchable(),
                TextColumn::make('rule_type')
                    ->label('Typ')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'ring_threshold' ? 'Ring-Schwelle' : 'Gold-Meilenstein'),
                TextColumn::make('age_from')
                    ->label('Von'),
                TextColumn::make('age_to')
                    ->label('Bis'),
                TextColumn::make('required_score')
                    ->label('Punktzahl'),
                TextColumn::make('required_gold_count')
                    ->label('Goldzahl'),
                TextColumn::make('award_name')
                    ->label('Auszeichnung')
                    ->searchable(),
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
            'index' => ListPlaqueAwardRules::route('/'),
            'create' => CreatePlaqueAwardRule::route('/create'),
            'edit' => EditPlaqueAwardRule::route('/{record}/edit'),
        ];
    }
}
