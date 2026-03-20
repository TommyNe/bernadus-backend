<?php

namespace App\Filament\Resources\Media\Pages;

use App\Filament\Resources\Media\MediumResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMedium extends EditRecord
{
    protected static string $resource = MediumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
