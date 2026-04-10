<?php

namespace App\Filament\Resources\JacketListings\Pages;

use App\Filament\Resources\JacketListings\JacketListingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJacketListings extends ListRecords
{
    protected static string $resource = JacketListingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
