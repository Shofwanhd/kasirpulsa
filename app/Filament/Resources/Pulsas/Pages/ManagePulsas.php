<?php

namespace App\Filament\Resources\Pulsas\Pages;

use App\Filament\Resources\Pulsas\PulsaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePulsas extends ManageRecords
{
    protected static string $resource = PulsaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
