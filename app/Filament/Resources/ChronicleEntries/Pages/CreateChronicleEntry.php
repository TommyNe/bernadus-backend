<?php

namespace App\Filament\Resources\ChronicleEntries\Pages;

use App\Filament\Resources\ChronicleEntries\ChronicleEntryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateChronicleEntry extends CreateRecord
{
    protected static string $resource = ChronicleEntryResource::class;
}
