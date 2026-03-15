<?php

namespace App\Filament\Resources\ServiceMaterials\Pages;

use App\Filament\Resources\ServiceMaterials\ServiceMaterialResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewServiceMaterial extends ViewRecord
{
    protected static string $resource = ServiceMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
