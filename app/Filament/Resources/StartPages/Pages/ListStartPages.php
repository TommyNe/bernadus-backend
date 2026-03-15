<?php

namespace App\Filament\Resources\StartPages\Pages;

use App\Filament\Resources\StartPages\StartPageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStartPages extends ListRecords
{
    protected static string $resource = StartPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
