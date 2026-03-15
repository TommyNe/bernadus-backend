<?php

namespace App\Filament\Resources\ContactEntries\Pages;

use App\Filament\Resources\ContactEntries\ContactEntryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListContactEntries extends ListRecords
{
    protected static string $resource = ContactEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
