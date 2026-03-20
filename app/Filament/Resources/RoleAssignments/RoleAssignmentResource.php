<?php

namespace App\Filament\Resources\RoleAssignments;

use App\Filament\Resources\RoleAssignments\Pages\CreateRoleAssignment;
use App\Filament\Resources\RoleAssignments\Pages\EditRoleAssignment;
use App\Filament\Resources\RoleAssignments\Pages\ListRoleAssignments;
use App\Models\RoleAssignment;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RoleAssignmentResource extends Resource
{
    protected static ?string $model = RoleAssignment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserPlus;

    protected static ?string $modelLabel = 'Amtszuweisung';

    protected static ?string $pluralModelLabel = 'Amtszuweisungen';

    protected static string|\UnitEnum|null $navigationGroup = 'Verein';

    protected static ?int $navigationSort = 30;

    protected static ?string $recordTitleAttribute = 'label_override';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('person_id')
                    ->relationship('person', 'display_name')
                    ->label('Person')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('role_id')
                    ->relationship('role', 'name')
                    ->label('Amt')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('label_override')
                    ->label('Angezeigter Text')
                    ->maxLength(255),
                DatePicker::make('started_on')
                    ->label('Beginn'),
                DatePicker::make('ended_on')
                    ->label('Ende'),
                Toggle::make('is_current')
                    ->label('Aktuell')
                    ->default(true)
                    ->required(),
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
            ->recordTitleAttribute('label_override')
            ->columns([
                TextColumn::make('person.display_name')
                    ->label('Person')
                    ->searchable(),
                TextColumn::make('role.name')
                    ->label('Amt')
                    ->searchable(),
                IconColumn::make('is_current')
                    ->label('Aktuell')
                    ->boolean(),
                TextColumn::make('started_on')
                    ->label('Beginn')
                    ->date('d.m.Y')
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('ended_on')
                    ->label('Ende')
                    ->date('d.m.Y')
                    ->placeholder('-')
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
            'index' => ListRoleAssignments::route('/'),
            'create' => CreateRoleAssignment::route('/create'),
            'edit' => EditRoleAssignment::route('/{record}/edit'),
        ];
    }
}
