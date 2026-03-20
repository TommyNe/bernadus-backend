<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\RoleAssignments\RoleAssignmentResource;
use App\Models\RoleAssignment;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class CurrentBoardWidget extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => RoleAssignment::query()
                ->with(['person', 'role'])
                ->where('is_current', true)
                ->orderBy('sort_order')
                ->orderBy('started_on'))
            ->columns([
                TextColumn::make('role.name')
                    ->label('Amt')
                    ->searchable(),
                TextColumn::make('person.display_name')
                    ->label('Person')
                    ->searchable(),
                TextColumn::make('label_override')
                    ->label('Angezeigte Bezeichnung')
                    ->placeholder('-')
                    ->toggleable(),
                TextColumn::make('started_on')
                    ->label('Seit')
                    ->date('d.m.Y')
                    ->placeholder('-'),
                IconColumn::make('is_current')
                    ->label('Aktuell')
                    ->boolean(),
            ])
            ->paginated(false)
            ->recordActions([
                EditAction::make()
                    ->url(fn (RoleAssignment $record): string => RoleAssignmentResource::getUrl('edit', ['record' => $record])),
            ])
            ->toolbarActions([]);
    }
}
