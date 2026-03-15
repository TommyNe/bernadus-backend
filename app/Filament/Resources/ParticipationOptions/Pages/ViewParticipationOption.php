<?php

namespace App\Filament\Resources\ParticipationOptions\Pages;

use App\Filament\Resources\ParticipationOptions\ParticipationOptionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewParticipationOption extends ViewRecord
{
    protected static string $resource = ParticipationOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
