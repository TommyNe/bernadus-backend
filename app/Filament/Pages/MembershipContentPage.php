<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class MembershipContentPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static string|\UnitEnum|null $navigationGroup = 'Inhalte';

    protected static ?string $navigationLabel = 'Mitglied werden';

    protected static ?int $navigationSort = 40;

    protected static ?string $title = 'Mitglied werden';

    protected static string $routePath = 'mitglied-werden';

    protected string $view = 'filament-panels::pages.page';
}
