<?php

namespace App\Filament\Resources\StartPages\Pages;

use App\Filament\Resources\StartPages\StartPageResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditStartPage extends EditRecord
{
    protected static string $resource = StartPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
