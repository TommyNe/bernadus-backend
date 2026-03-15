<?php

namespace App\Filament\Resources\AboutSections\Pages;

use App\Filament\Resources\AboutSections\AboutSectionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAboutSection extends ViewRecord
{
    protected static string $resource = AboutSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
