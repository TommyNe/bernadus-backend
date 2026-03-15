<?php

namespace App\Filament\Resources\GalleryHonors\Pages;

use App\Filament\Resources\GalleryHonors\GalleryHonorResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewGalleryHonor extends ViewRecord
{
    protected static string $resource = GalleryHonorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
