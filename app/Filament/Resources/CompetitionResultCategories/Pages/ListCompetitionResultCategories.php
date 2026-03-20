<?php

namespace App\Filament\Resources\CompetitionResultCategories\Pages;

use App\Filament\Resources\CompetitionResultCategories\CompetitionResultCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCompetitionResultCategories extends ListRecords
{
    protected static string $resource = CompetitionResultCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
