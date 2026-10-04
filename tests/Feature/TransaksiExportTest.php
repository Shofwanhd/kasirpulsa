<?php

use App\Exports\TransaksiExport;
use App\Models\Transaksi;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

it('exports transactions matching the selected date range and type', function () {
    $includedTransaction = Transaksi::query()->create([
        'jenis_transaksi' => 'Transfer',
        'tanggal' => '2026-10-05',
        'keterangan' => 'Transfer dalam periode',
        'nominal' => 10000,
        'amount' => 12500,
    ]);

    Transaksi::query()->create([
        'jenis_transaksi' => 'Pulsa',
        'tanggal' => '2026-10-05',
        'keterangan' => 'Jenis berbeda',
        'nominal' => 10000,
        'amount' => 12000,
    ]);

    Transaksi::query()->create([
        'jenis_transaksi' => 'Transfer',
        'tanggal' => '2026-11-05',
        'keterangan' => 'Di luar periode',
        'nominal' => 10000,
        'amount' => 12500,
    ]);

    $export = new TransaksiExport('2026-10-01', '2026-10-31', 'Transfer');
    $transactions = $export->collection();

    expect($transactions->modelKeys())->toBe([$includedTransaction->id]);
    expect($export->headings())->toBe([
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
    ]);

    $mappedTransaction = $export->map($transactions->first());

    expect($mappedTransaction[0])->toBe('Transfer');
    expect($mappedTransaction[1])->toBe('2026-10-05');
    expect($mappedTransaction[2])->toBe('Transfer dalam periode');
    expect((float) $mappedTransaction[7])->toBe(10000.0);
    expect((float) $mappedTransaction[8])->toBe(12500.0);
    expect((float) $mappedTransaction[9])->toBe(2500.0);
});
