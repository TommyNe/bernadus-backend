<?php

namespace App\Filament\Resources\ServiceMaterials\Pages;

use App\Filament\Resources\ServiceMaterials\ServiceMaterialResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditServiceMaterial extends EditRecord
{
    protected static string $resource = ServiceMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
