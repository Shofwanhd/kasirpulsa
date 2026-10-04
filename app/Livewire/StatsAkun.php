<?php

namespace App\Livewire;

use App\Models\Akun;
use App\Services\SaldoService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsAkun extends BaseWidget
{
    protected function getStats(): array
    {
        $saldoService = app(SaldoService::class);
        $tanggalHariIni = today();

        $akunsPerKategori = Akun::query()
            ->with('kategoriAkun')
            ->orderBy('nama_akun')
            ->get()
            ->groupBy('kategori_akun_id')
            ->sortKeys();

        $stats = [];

        foreach ($akunsPerKategori as $akuns) {
            $kategori = $akuns->first()?->kategoriAkun?->kategori ?? 'Tanpa Kategori';
            $totalSaldo = $akuns->sum(
                fn (Akun $akun): float => $saldoService->saldoPada($akun, $tanggalHariIni),
            );

            $stats[] = Stat::make(
                'Saldo '.$kategori,
                'Rp '.number_format($totalSaldo, 0, ',', '.'),
            );
        }

        return $stats;
    }
}
