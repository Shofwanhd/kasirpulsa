<?php

namespace App\Services;

use App\Models\Akun;
use App\Models\Transaksi;
use Illuminate\Support\Carbon;

class SaldoService
{
    public function saldoPada(Akun $akun, $tanggal): float
    {
        $tanggal = Carbon::parse($tanggal);

        $saldoAwal = $akun->saldo_awal;

        $totalMasuk = Transaksi::where('akun_tujuan_id', $akun->id)
            ->whereDate('tanggal', '<=', $tanggal)
            ->sum('amount');

        $totalKeluar = Transaksi::where('akun_asal_id', $akun->id)
            ->whereDate('tanggal', '<=', $tanggal)
            ->sum('nominal');

        return $saldoAwal + $totalMasuk - $totalKeluar;
    }

    public function saldoAwalPeriode(Akun $akun, $tanggalMulai): float
    {
        $tanggalMulai = Carbon::parse($tanggalMulai);

        return $this->saldoPada(
            $akun,
            $tanggalMulai->copy()->subDay()
        );
    }

    public function saldoAkhirPeriode(Akun $akun, $tanggalAkhir): float
    {
        return $this->saldoPada($akun, $tanggalAkhir);
    }
}
