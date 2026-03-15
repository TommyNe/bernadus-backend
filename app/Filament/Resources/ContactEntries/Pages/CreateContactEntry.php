<?php

namespace App\Filament\Resources\ContactEntries\Pages;

use App\Filament\Resources\ContactEntries\ContactEntryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContactEntry extends CreateRecord
{
    protected static string $resource = ContactEntryResource::class;
}
