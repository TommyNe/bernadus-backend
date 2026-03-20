<?php

namespace App\Filament\Resources\Chronicles\Pages;

use App\Filament\Resources\Chronicles\ChronicleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListChronicles extends ListRecords
{
    protected static string $resource = ChronicleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
