<?php

namespace App\Filament\Resources\JacketListings\Pages;

use App\Filament\Resources\JacketListings\JacketListingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJacketListing extends EditRecord
{
    protected static string $resource = JacketListingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
