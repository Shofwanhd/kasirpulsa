<?php

use App\Exports\LedgerExport;
use App\Models\Akun;
use App\Models\KategoriAkun;
use App\Models\Transaksi;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

it('exports opening and closing balances for only the selected account', function () {
    $kategoriAkun = KategoriAkun::query()->create(['kategori' => 'Bank']);
    $akun = Akun::query()->create([
        'kategori_akun_id' => $kategoriAkun->id,
        'nama_akun' => 'BCA',
        'saldo_awal' => 100000,
    ]);
    $akunLain = Akun::query()->create([
        'kategori_akun_id' => $kategoriAkun->id,
        'nama_akun' => 'BRI',
        'saldo_awal' => 50000,
    ]);

    Transaksi::query()->create([
        'jenis_transaksi' => 'Transfer',
        'tanggal' => '2026-09-30',
        'keterangan' => 'Saldo masuk sebelum periode',
        'akun_tujuan_id' => $akun->id,
        'amount' => 20000,
    ]);

    Transaksi::query()->create([
        'jenis_transaksi' => 'Transfer',
        'tanggal' => '2026-09-30',
        'keterangan' => 'Saldo keluar sebelum periode',
        'akun_asal_id' => $akun->id,
        'nominal' => 5000,
    ]);

    $transaksiMasuk = Transaksi::query()->create([
        'jenis_transaksi' => 'Transfer',
        'tanggal' => '2026-10-05',
        'keterangan' => 'Saldo masuk dalam periode',
        'akun_tujuan_id' => $akun->id,
        'amount' => 15000,
    ]);

    $transaksiKeluar = Transaksi::query()->create([
        'jenis_transaksi' => 'Transfer',
        'tanggal' => '2026-10-06',
        'keterangan' => 'Saldo keluar dalam periode',
        'akun_asal_id' => $akun->id,
        'nominal' => 7000,
    ]);

    Transaksi::query()->create([
        'jenis_transaksi' => 'Transfer',
        'tanggal' => '2026-10-05',
        'keterangan' => 'Transaksi akun lain',
        'akun_tujuan_id' => $akunLain->id,
        'amount' => 800000,
    ]);

    $rows = (new LedgerExport('2026-10-01', '2026-10-31', (string) $akun->id))->array();

    expect($rows)->toHaveCount(4);
    expect($rows[0][2])->toBe('Saldo Awal');
    expect((float) $rows[0][7])->toBe(115000.0);
    expect($rows[1][2])->toBe('Saldo masuk dalam periode');
    expect($rows[1][0])->toBe($transaksiMasuk->tanggal);
    expect($rows[1][5])->toBeNull();
    expect((float) $rows[1][6])->toBe(15000.0);
    expect($rows[2][2])->toBe('Saldo keluar dalam periode');
    expect($rows[2][0])->toBe($transaksiKeluar->tanggal);
    expect((float) $rows[2][5])->toBe(-7000.0);
    expect($rows[2][6])->toBeNull();
    expect($rows[3][2])->toBe('Saldo Akhir');
    expect((float) $rows[3][7])->toBe(123000.0);
});
