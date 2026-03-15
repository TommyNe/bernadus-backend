<?php

namespace App\Filament\Resources\ServiceMaterials;

use App\Filament\Resources\ContentResource;
use App\Filament\Resources\ServiceMaterials\Pages\CreateServiceMaterial;
use App\Filament\Resources\ServiceMaterials\Pages\EditServiceMaterial;
use App\Filament\Resources\ServiceMaterials\Pages\ListServiceMaterials;
use App\Filament\Resources\ServiceMaterials\Pages\ViewServiceMaterial;
use App\Models\ServiceMaterial;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class ServiceMaterialResource extends ContentResource
{
    protected static ?string $model = ServiceMaterial::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static ?string $navigationLabel = 'Service & Material';

    protected static ?int $navigationSort = 30;

    protected static ?string $modelLabel = 'Serviceeintrag';

    protected static ?string $pluralModelLabel = 'Serviceeinträge';

    protected static ?string $recordTitleAttribute = 'title';

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListServiceMaterials::route('/'),
            'create' => CreateServiceMaterial::route('/create'),
            'view' => ViewServiceMaterial::route('/{record}'),
            'edit' => EditServiceMaterial::route('/{record}/edit'),
        ];
    }
}
