<?php

namespace App\Filament\Resources\Flyers\Pages;

use App\Filament\Resources\Flyers\FlyerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFlyer extends EditRecord
{
    protected static string $resource = FlyerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
