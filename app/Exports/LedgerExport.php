<?php

namespace App\Exports;

use App\Models\Akun;
use App\Models\Transaksi;
use App\Services\SaldoService;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LedgerExport implements FromArray, WithHeadings
{
    public function __construct(
        protected ?string $tanggalAwal,
        protected ?string $tanggalAkhir,
        protected ?string $akunId,
    ) {}

    public function array(): array
    {
        $akun = Akun::query()->findOrFail($this->akunId);
        $saldoService = app(SaldoService::class);

        $transaksis = Transaksi::query()
            ->with(['akunAsal', 'akunTujuan'])
            ->whereBetween('tanggal', [$this->tanggalAwal, $this->tanggalAkhir])
            ->where(fn (Builder $query): Builder => $query
                ->where('akun_asal_id', $akun->id)
                ->orWhere('akun_tujuan_id', $akun->id))
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get();

        $rows = [[
            $this->tanggalAwal,
            '',
            'Saldo Awal',
            $akun->nama_akun,
            '',
            null,
            null,
            $saldoService->saldoAwalPeriode($akun, $this->tanggalAwal),
        ]];

        foreach ($transaksis as $transaksi) {
            $isAkunAsal = (int) $transaksi->akun_asal_id === (int) $akun->id;
            $isAkunTujuan = (int) $transaksi->akun_tujuan_id === (int) $akun->id;

            $rows[] = [
                $transaksi->tanggal,
                $transaksi->jenis_transaksi,
                $transaksi->keterangan,
                $transaksi->akunAsal?->nama_akun,
                $transaksi->akunTujuan?->nama_akun,
                $isAkunAsal ? -abs((float) $transaksi->nominal) : null,
                $isAkunTujuan ? abs((float) $transaksi->amount) : null,
                null,
            ];
        }

        $rows[] = [
            $this->tanggalAkhir,
            '',
            'Saldo Akhir',
            $akun->nama_akun,
            '',
            null,
            null,
            $saldoService->saldoAkhirPeriode($akun, $this->tanggalAkhir),
        ];

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Jenis Transaksi',
            'Keterangan',
            'Akun Asal',
            'Akun Tujuan',
            'Debit',
            'Credit',
            'Saldo',
        ];
    }
}
