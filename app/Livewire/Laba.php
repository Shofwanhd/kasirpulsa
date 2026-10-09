<?php

namespace App\Livewire;

use App\Services\LabaService;
use \App\Models\Transaksi;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Laba extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        //perhitungan laba today
        $labaService = app(LabaService::class);
        $tanggalHariIni = today()->toDateString();
        $labaHariIni = $labaService->labaHariIni($tanggalHariIni);

        //perhitungan laba this month
        $tanggalAwalBulanIni = today()->startOfMonth()->toDateString();
        $tanggalAkhirBulanIni = today()->endOfMonth()->toDateString();
        $labaBulanIni = $labaService->labaPeriode($tanggalAwalBulanIni, $tanggalAkhirBulanIni);

        //laba keseluruhan
        $labaKeseluruhan = $labaService->labaKeseluruhan();

        return [
            Stat::make('Laba Hari ini', 'Rp ' . number_format($labaHariIni, 0, ',', '.')),
            Stat::make('Laba Bulan Ini', 'Rp ' . number_format($labaBulanIni, 0, ',', '.')),
            Stat::make('Laba Keseluruhan', 'Rp ' . number_format($labaKeseluruhan, 0, ',', '.')),
        ];
    }
}
