<?php

namespace App\Livewire;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Versioning extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Version', '')
                ->description('V1.0.0 Beta')
                ->url('readme.md'),
        ];
    }
}
