<?php

namespace App\Filament\Resources\GalleryHonors;

use App\Filament\Resources\ContentResource;
use App\Filament\Resources\GalleryHonors\Pages\CreateGalleryHonor;
use App\Filament\Resources\GalleryHonors\Pages\EditGalleryHonor;
use App\Filament\Resources\GalleryHonors\Pages\ListGalleryHonors;
use App\Filament\Resources\GalleryHonors\Pages\ViewGalleryHonor;
use App\Models\GalleryHonor;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class GalleryHonorResource extends ContentResource
{
    protected static ?string $model = GalleryHonor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static ?string $navigationLabel = 'Galerie & Ehrungen';

    protected static ?int $navigationSort = 70;

    protected static ?string $modelLabel = 'Galerieeintrag';

    protected static ?string $pluralModelLabel = 'Galerieeinträge';

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
            'index' => ListGalleryHonors::route('/'),
            'create' => CreateGalleryHonor::route('/create'),
            'view' => ViewGalleryHonor::route('/{record}'),
            'edit' => EditGalleryHonor::route('/{record}/edit'),
        ];
    }
}
