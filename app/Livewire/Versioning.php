<?php

namespace App\Livewire;

use Filament\Actions\Action;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Versioning extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Version', '')
                ->description('V1.1.0 Beta')
                ->url('https://docs.google.com/document/d/1ewtbtvLuIU6K0gXQrM9BmeqXcrMLXWn5538FdoHheS0/edit?usp=sharing'),
        ];
    }
}
