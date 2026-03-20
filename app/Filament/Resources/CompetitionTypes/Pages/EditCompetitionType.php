<?php

namespace App\Filament\Resources\CompetitionTypes\Pages;

use App\Filament\Resources\CompetitionTypes\CompetitionTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCompetitionType extends EditRecord
{
    protected static string $resource = CompetitionTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
