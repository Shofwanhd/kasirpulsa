<?php

namespace App\Filament\Pages;

use App\Exports\LedgerExport;
use App\Models\Akun;
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

class LaporanLedger extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.laporan-ledger';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = 'Laporan';

    protected static ?string $title = 'Laporan Ledger';

    protected static ?string $pluralModelLabel = 'Laporan Ledger';

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
                        Select::make('akun_id')
                            ->label('Pilih Akun')
                            ->options(fn (): array => Akun::query()
                                ->orderBy('nama_akun')
                                ->pluck('nama_akun', 'id')
                                ->all())
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
        $namaAkun = $data['akun_id'];

        // 3. Buat nama file
        $namaFile = 'laporan-ledger-'
            .$tanggalAwal
            .'-sd-'
            .$tanggalAkhir
            .'-'
            .str($namaAkun)->slug()
            .'.xlsx';

        // 4. Download Excel
        return app(ExcelManager::class)->download(
            new LedgerExport(
                $tanggalAwal,
                $tanggalAkhir,
                $namaAkun,
            ),
            $namaFile
        );
    }
}
