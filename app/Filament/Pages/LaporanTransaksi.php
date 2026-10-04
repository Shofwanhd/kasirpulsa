<?php

namespace App\Filament\Pages;

use App\Exports\TransaksiExport;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Maatwebsite\Excel\Excel as ExcelManager;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use UnitEnum;

class LaporanTransaksi extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.laporan-transaksi';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = 'Laporan';

    protected static ?string $title = 'Laporan Transaksi';

    protected static ?string $pluralModelLabel = 'Laporan Transaksi';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form($form)
    {
        return $form
            ->schema([
                Section::make('Filter Laporan')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                DatePicker::make('Sdate')
                                    ->label('Start')
                                    ->required(),
                                DatePicker::make('Edate')
                                    ->label('End')
                                    ->required(),
                            ]),
                        Select::make('jenis_transaksi')
                            ->label('Jenis Transaksi')
                            ->options([
                                'Transfer' => 'Transfer',
                                'Tarik Tunai' => 'Tarik Tunai',
                                'Pulsa' => 'Pulsa',
                                'Mutasi' => 'Mutasi',
                            ])
                            // ->options(fn(): array => ['ALL' => 'ALL'] + Akun::query()
                            //     ->orderBy('nama_akun')
                            //     ->pluck('nama_akun', 'id')
                            //     ->all())
                            // ->default('ALL')
                            ->required(),
                    ]),
            ])->statePath('data');
    }

    public function downloadExcel(): BinaryFileResponse
    {
        // 1. Ambil nilai dari form sekaligus validasi
        $data = $this->form->getState();

        // 2. Ambil tanggal yang dipilih
        $tanggalAwal = $data['Sdate'];
        $tanggalAkhir = $data['Edate'];
        $jenisTransaksi = $data['jenis_transaksi'];

        // 3. Buat nama file
        $namaFile = 'laporan-transaksi-'
            .$tanggalAwal
            .'-sd-'
            .$tanggalAkhir
            .'-'
            .str($jenisTransaksi)->slug()
            .'.xlsx';

        // 4. Download Excel
        return app(ExcelManager::class)->download(
            new TransaksiExport(
                $tanggalAwal,
                $tanggalAkhir,
                $jenisTransaksi,
            ),
            $namaFile
        );
    }
}
