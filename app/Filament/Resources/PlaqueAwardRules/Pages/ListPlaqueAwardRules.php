<?php

namespace App\Filament\Resources\PlaqueAwardRules\Pages;

use App\Filament\Resources\PlaqueAwardRules\PlaqueAwardRuleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPlaqueAwardRules extends ListRecords
{
    protected static string $resource = PlaqueAwardRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
