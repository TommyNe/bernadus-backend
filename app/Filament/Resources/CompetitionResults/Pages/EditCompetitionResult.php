<?php

namespace App\Filament\Resources\CompetitionResults\Pages;

use App\Filament\Resources\CompetitionResults\CompetitionResultResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCompetitionResult extends EditRecord
{
    protected static string $resource = CompetitionResultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
