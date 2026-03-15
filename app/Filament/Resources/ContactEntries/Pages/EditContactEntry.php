<?php

namespace App\Filament\Resources\ContactEntries\Pages;

use App\Filament\Resources\ContactEntries\ContactEntryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditContactEntry extends EditRecord
{
    protected static string $resource = ContactEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
