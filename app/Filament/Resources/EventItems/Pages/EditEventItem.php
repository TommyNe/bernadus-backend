<?php

namespace App\Filament\Resources\EventItems\Pages;

use App\Filament\Resources\EventItems\EventItemResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditEventItem extends EditRecord
{
    protected static string $resource = EventItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
