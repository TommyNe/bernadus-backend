<?php

namespace App\Filament\Resources\CompetitionResultCategories\Pages;

use App\Filament\Resources\CompetitionResultCategories\CompetitionResultCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCompetitionResultCategory extends EditRecord
{
    protected static string $resource = CompetitionResultCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
