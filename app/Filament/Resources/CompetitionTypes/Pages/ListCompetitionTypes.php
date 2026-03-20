<?php

namespace App\Filament\Resources\CompetitionTypes\Pages;

use App\Filament\Resources\CompetitionTypes\CompetitionTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCompetitionTypes extends ListRecords
{
    protected static string $resource = CompetitionTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
