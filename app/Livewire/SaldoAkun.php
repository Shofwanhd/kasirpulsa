<?php

namespace App\Livewire;

use App\Models\Akun;
use App\Services\SaldoService;
use Filament\Widgets\ChartWidget;

class SaldoAkun extends ChartWidget
{
    protected ?string $heading = 'Saldo Akun';

    protected function getData(): array
    {
        $saldoService = app(SaldoService::class);
        $tanggalHariIni = today();
        $akuns = Akun::query()
            ->orderBy('nama_akun')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Saldo',
                    'data' => $akuns
                        ->map(fn (Akun $akun): float => $saldoService->saldoPada($akun, $tanggalHariIni))
                        ->all(),
                ],
            ],
            'labels' => $akuns->pluck('nama_akun')->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
