<?php

namespace App\Filament\Resources\ParticipationOptions\Pages;

use App\Filament\Resources\ParticipationOptions\ParticipationOptionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListParticipationOptions extends ListRecords
{
    protected static string $resource = ParticipationOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
