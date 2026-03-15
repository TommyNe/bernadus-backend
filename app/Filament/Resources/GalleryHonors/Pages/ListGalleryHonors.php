<?php

namespace App\Filament\Resources\GalleryHonors\Pages;

use App\Filament\Resources\GalleryHonors\GalleryHonorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGalleryHonors extends ListRecords
{
    protected static string $resource = GalleryHonorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
