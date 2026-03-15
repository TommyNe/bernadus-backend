<?php

namespace App\Filament\Resources\EventItems\Pages;

use App\Filament\Resources\EventItems\EventItemResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEventItem extends ViewRecord
{
    protected static string $resource = EventItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
