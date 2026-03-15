<?php

namespace App\Filament\Resources\GalleryHonors\Pages;

use App\Filament\Resources\GalleryHonors\GalleryHonorResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditGalleryHonor extends EditRecord
{
    protected static string $resource = GalleryHonorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
