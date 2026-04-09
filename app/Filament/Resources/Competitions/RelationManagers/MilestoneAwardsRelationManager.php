<?php

namespace App\Filament\Resources\Competitions\RelationManagers;

use App\Models\Competition;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class MilestoneAwardsRelationManager extends RelationManager
{
    protected static string $relationship = 'milestoneAwards';

    protected static ?string $title = 'Meilenstein-Auszeichnungen';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord instanceof Competition
            && $ownerRecord->type?->type_key === 'plaque_shooting';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('threshold')
                    ->label('Schwelle')
                    ->required()
                    ->maxLength(255),
                TextInput::make('award')
                    ->label('Auszeichnung')
                    ->required()
                    ->maxLength(255),
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
            ->recordTitleAttribute('threshold')
            ->columns([
                TextColumn::make('threshold')
                    ->label('Schwelle')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('award')
                    ->label('Auszeichnung')
                    ->searchable(),
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
