<?php

namespace App\Filament\Resources\Chronicles\Pages;

use App\Filament\Resources\Chronicles\ChronicleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditChronicle extends EditRecord
{
    protected static string $resource = ChronicleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
