<?php

namespace App\Services;

use App\Models\Transaksi;

class LabaService
{
    /**
     * Menghitung laba dari satu transaksi.
     */
    public function labaTransaksi(Transaksi $transaksi): float
    {
        return (float) $transaksi->amount
            - (float) $transaksi->nominal;
    }

    /**
     * Menghitung total laba dalam suatu periode.
     */
    public function labaPeriode(
        string $tanggalAwal,
        string $tanggalAkhir
    ): float {
        return (float) Transaksi::query()
            ->whereBetween('tanggal', [
                $tanggalAwal,
                $tanggalAkhir,
            ])
            ->selectRaw(
                'COALESCE(SUM(amount - nominal), 0) as total_laba'
            )
            ->value('total_laba');
    }

    public function labaHariIni(
        string $today,
    ): float {
        return (float) Transaksi::query()
            ->whereBetween('tanggal', [
                $today,
                $today,
            ])
            ->selectRaw(
                'COALESCE(SUM(amount - nominal), 0) as total_laba'
            )
            ->value('total_laba');
    }

    public function labaKeseluruhan(): float
    {
        return (float) Transaksi::query()
            ->selectRaw(
                'COALESCE(SUM(amount - nominal), 0) as total_laba'
            )
            ->value('total_laba');
    }
}
