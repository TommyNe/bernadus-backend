<?php

namespace App\Filament\Resources\ChronicleEntries\Pages;

use App\Filament\Resources\ChronicleEntries\ChronicleEntryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditChronicleEntry extends EditRecord
{
    protected static string $resource = ChronicleEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
