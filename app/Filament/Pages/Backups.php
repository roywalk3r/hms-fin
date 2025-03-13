<?php

namespace App\Filament\Pages;

use ShuvroRoy\FilamentSpatieLaravelBackup\Pages\Backups as BaseBackups;
class Backups extends BaseBackups
{
    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';
    public function getHeading(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return 'Application Backups';
    }
    public static function getNavigationGroup(): ?string
    {
        return 'System Tools';
    }

}
