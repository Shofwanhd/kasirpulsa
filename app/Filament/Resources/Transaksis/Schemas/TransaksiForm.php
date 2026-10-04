<?php

namespace App\Filament\Resources\Transaksis\Schemas;

use App\Models\Akun;
use App\Models\KategoriPulsa;
use App\Models\Pulsa;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;

class TransaksiForm
{
    public static function configure(Schema $schema): Schema
    {
        $kasTunaiId = Akun::query()->where('nama_akun', 'Kas Tunai')->value('id');
        $serverPulsaId = Akun::query()->where('nama_akun', 'Server Pulsa')->value('id');

        $labelsByType = [
            'Transfer' => [
                'tanggal' => 'Tanggal Transaksi',
                'keterangan' => 'Nama / Keterangan Transfer',
                'akun_asal_id' => 'Transfer Dari',
                'akun_tujuan_id' => 'Pelanggan Bayar Via',
                'nominal' => 'Nominal Transfer',
                'admin' => 'Admin Transfer',
                'amount' => 'Dibayar Pelanggan',
            ],
            'Tarik Tunai' => [
                'tanggal' => 'Tanggal Transaksi',
                'keterangan' => 'Nama / Keterangan Penarikan',
                'akun_asal_id' => 'Akun Kas Tunai (Kas Tunai)',
                'akun_tujuan_id' => 'Dana Masuk Ke',
                'nominal' => 'Nominal Tarik Tunai',
                'admin' => 'Admin Penarikan',
                'amount' => 'Dibayar Pelanggan',
            ],
            'Pulsa' => [
                'tanggal' => 'Tanggal Transaksi',
                'keterangan' => 'Produk / Nomor Tujuan',
                'akun_asal_id' => 'Akun Server Pulsa',
                'akun_tujuan_id' => 'Akun Pelanggan Bayar Via',
                'nominal' => 'Harga Modal',
                'admin' => 'Admin',
                'amount' => 'Harga Jual',
            ],
            'Mutasi' => [
                'tanggal' => 'Tanggal Transaksi',
                'keterangan' => 'Keterangan Mutasi',
                'akun_asal_id' => 'Akun Asal Mutasi',
                'akun_tujuan_id' => 'Akun Tujuan Mutasi',
                'nominal' => 'Nominal Mutasi',
                'admin' => 'Admin Mutasi',
                'amount' => 'amount Mutasi',
            ],
        ];

        $labelFor = fn(Get $get, string $field, string $fallback): string => $labelsByType[$get('jenis_transaksi') ?? ''][$field] ?? $fallback;
        $isTypeSelected = fn(Get $get): bool => filled($get('jenis_transaksi'));
        $isKasTunaiSelected = fn(Get $get): bool => filled($kasTunaiId) && (string) $get('akun_asal_id') === (string) $kasTunaiId;

        return $schema
            ->components([
                Select::make('jenis_transaksi')
                    ->label('Jenis Transaksi')
                    ->options([
                        'Transfer' => 'Transfer',
                        'Tarik Tunai' => 'Tarik Tunai',
                        'Pulsa' => 'Pulsa',
                        'Mutasi' => 'Mutasi',
                    ])
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Set $set, ?string $state) use ($kasTunaiId, $serverPulsaId): void {
                        $set(
                            'akun_asal_id',
                            match ($state) {
                                'Tarik Tunai' => $kasTunaiId,
                                'Pulsa' => $serverPulsaId,
                                default => null,
                            },
                        );
                    }),
                Section::make('Detail Transaksi')
                    ->schema([
                        DatePicker::make('tanggal')
                            ->label(fn(Get $get): string => $labelFor($get, 'tanggal', 'Tanggal'))
                            ->required(),
                        Select::make('kategori_pulsa_id')
                            ->label(fn(Get $get): string => $labelFor($get, 'kategori_pulsa_id', 'Kategori Pulsa'))
                            ->options(fn(): array => KategoriPulsa::query()->pluck('kategori', 'id')->toArray())
                            ->required(fn(Get $get): bool => $get('jenis_transaksi') === 'Pulsa')
                            ->visible(fn(Get $get): bool => $get('jenis_transaksi') === 'Pulsa')
                            ->live()
                            ->afterStateUpdated(function (Set $set): void {
                                $set('pulsa_id', null);
                                $set('nominal', null);
                            }),
                        Select::make('pulsa_id')
                            ->label(fn(Get $get): string => $labelFor($get, 'pulsa_id', 'Pulsa'))
                            ->options(fn(Get $get): array => filled($get('kategori_pulsa_id'))
                                ? Pulsa::query()
                                ->where('kategori_pulsa_id', $get('kategori_pulsa_id'))
                                ->pluck('pulsa', 'id')
                                ->toArray()
                                : [])
                            ->required(fn(Get $get): bool => $get('jenis_transaksi') === 'Pulsa')
                            ->visible(fn(Get $get): bool => $get('jenis_transaksi') === 'Pulsa')
                            ->disabled(fn(Get $get): bool => blank($get('kategori_pulsa_id')))
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?string $state): void {
                                $set(
                                    'nominal',
                                    filled($state) ? Pulsa::query()->whereKey($state)->value('harga') : null,
                                );
                            }),
                        TextInput::make('keterangan')
                            ->label(fn(Get $get): string => $labelFor($get, 'keterangan', 'Keterangan'))
                            ->required(),
                        Select::make('akun_asal_id')
                            ->label(fn(Get $get): string => $labelFor($get, 'akun_asal_id', 'Akun Asal'))
                            ->required(fn(Get $get): bool => $get('jenis_transaksi') !== 'Mutasi')
                            ->options(Akun::query()->pluck('nama_akun', 'id'))
                            ->visible(fn(Get $get): bool => $get('jenis_transaksi') !== 'Tarik Tunai' || ! $isKasTunaiSelected($get))
                            ->dehydratedWhenHidden(fn(Get $get): bool => $get('jenis_transaksi') === 'Tarik Tunai' && $isKasTunaiSelected($get)),
                        Select::make('akun_tujuan_id')
                            ->label(fn(Get $get): string => $labelFor($get, 'akun_tujuan_id', 'Akun Tujuan'))
                            ->required(fn(Get $get): bool => $get('jenis_transaksi') !== 'Mutasi')
                            ->options(Akun::query()->pluck('nama_akun', 'id')),
                        TextInput::make('nominal')
                            ->label(fn(Get $get): string => $labelFor($get, 'nominal', 'Nominal'))
                            ->required()
                            ->numeric()
                            ->inputMode('decimal')
                            // Format parameters: $money($input, 'decimal_separator', 'thousands_separator', precision)
                            ->mask(RawJs::make(<<<'JS'
                                $money($input, '.', ',', 2)
                            JS))->stripCharacters(',')->dehydrateStateUsing(fn($state) => $state !== null ? (float) $state : null)
                            ->default(0),
                        TextInput::make('amount')
                            ->label(fn(Get $get): string => $labelFor($get, 'amount', 'Amount'))
                            ->required()
                            ->numeric()
                            ->mask(RawJs::make(<<<'JS'
                                $money($input, '.', ',', 2)
                            JS))->stripCharacters(',')->dehydrateStateUsing(fn($state) => $state !== null ? (float) $state : null)
                            ->default(0),
                    ])
                    ->visible($isTypeSelected),
            ]);
    }
}
