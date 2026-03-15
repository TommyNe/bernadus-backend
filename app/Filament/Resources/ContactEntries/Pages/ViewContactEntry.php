<?php

namespace App\Filament\Resources\ContactEntries\Pages;

use App\Filament\Resources\ContactEntries\ContactEntryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewContactEntry extends ViewRecord
{
    protected static string $resource = ContactEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
