<?php

namespace App\Filament\Resources\RoleAssignments\Pages;

use App\Filament\Resources\RoleAssignments\RoleAssignmentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRoleAssignment extends EditRecord
{
    protected static string $resource = RoleAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
