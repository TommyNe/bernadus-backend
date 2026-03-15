<?php

namespace App\Filament\Resources\ServiceMaterials\Pages;

use App\Filament\Resources\ServiceMaterials\ServiceMaterialResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListServiceMaterials extends ListRecords
{
    protected static string $resource = ServiceMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
