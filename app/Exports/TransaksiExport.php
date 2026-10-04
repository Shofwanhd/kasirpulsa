<?php

namespace App\Exports;

use App\Models\Transaksi;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransaksiExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        protected ?string $tanggalAwal,
        protected ?string $tanggalAkhir,
        protected ?string $jenisTransaksi,
    ) {}

    public function headings(): array
    {
        return [
            'Jenis Transaksi',
            'Tanggal',
            'Keterangan',
            'Kategori Pulsa',
            'Produk Pulsa',
            'Modal Dari',
            'Dibayar Via',
            'Modal',
            'Dibayar',
            'Profit',
        ];
    }

    public function collection(): Collection
    {
        return Transaksi::query()
            ->with(['kategoriPulsa', 'pulsa', 'akunAsal', 'akunTujuan'])
            ->when(
                $this->tanggalAwal,
                fn (Builder $query): Builder => $query->whereDate(
                    'tanggal',
                    '>=',
                    $this->tanggalAwal
                )
            )
            ->when(
                $this->tanggalAkhir,
                fn (Builder $query): Builder => $query->whereDate(
                    'tanggal',
                    '<=',
                    $this->tanggalAkhir
                )
            )
            ->when(
                $this->jenisTransaksi,
                fn (Builder $query): Builder => $query->where(
                    'jenis_transaksi',
                    $this->jenisTransaksi
                )
            )
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get();
    }

    /** @param Transaksi $transaksi */
    public function map(mixed $transaksi): array
    {
        return [
            $transaksi->jenis_transaksi,
            $transaksi->tanggal,
            $transaksi->keterangan,
            $transaksi->kategoriPulsa?->kategori,
            $transaksi->pulsa?->pulsa,
            $transaksi->akunAsal?->nama_akun,
            $transaksi->akunTujuan?->nama_akun,
            $transaksi->nominal,
            $transaksi->amount,
            $transaksi->profit,
        ];
    }
}
