<?php

namespace App\Filament\Resources\CompetitionResults\Pages;

use App\Filament\Resources\CompetitionResults\CompetitionResultResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCompetitionResults extends ListRecords
{
    protected static string $resource = CompetitionResultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
