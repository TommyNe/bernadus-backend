<?php

namespace App\Filament\Resources\PlaqueAwardRules\Pages;

use App\Filament\Resources\PlaqueAwardRules\PlaqueAwardRuleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPlaqueAwardRule extends EditRecord
{
    protected static string $resource = PlaqueAwardRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
