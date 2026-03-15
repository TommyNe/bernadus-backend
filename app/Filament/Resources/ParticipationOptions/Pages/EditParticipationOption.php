<?php

namespace App\Filament\Resources\ParticipationOptions\Pages;

use App\Filament\Resources\ParticipationOptions\ParticipationOptionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditParticipationOption extends EditRecord
{
    protected static string $resource = ParticipationOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
