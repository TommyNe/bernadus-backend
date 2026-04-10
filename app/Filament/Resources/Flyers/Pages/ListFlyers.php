<?php

namespace App\Filament\Resources\Flyers\Pages;

use App\Filament\Resources\Flyers\FlyerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFlyers extends ListRecords
{
    protected static string $resource = FlyerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
