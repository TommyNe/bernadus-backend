<?php

namespace App\Filament\Resources\ChronicleEntries\Pages;

use App\Filament\Resources\ChronicleEntries\ChronicleEntryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListChronicleEntries extends ListRecords
{
    protected static string $resource = ChronicleEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
