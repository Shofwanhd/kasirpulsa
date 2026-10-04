<?php

namespace App\Filament\Resources\KategoriAkuns\Pages;

use App\Filament\Resources\KategoriAkuns\KategoriAkunResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageKategoriAkuns extends ManageRecords
{
    protected static string $resource = KategoriAkunResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
