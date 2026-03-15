<?php

namespace App\Filament\Resources\StartPages\Pages;

use App\Filament\Resources\StartPages\StartPageResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStartPage extends ViewRecord
{
    protected static string $resource = StartPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
