<?php

namespace App\Filament\Resources\KategoriPulsas\Pages;

use App\Filament\Resources\KategoriPulsas\KategoriPulsaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageKategoriPulsas extends ManageRecords
{
    protected static string $resource = KategoriPulsaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
