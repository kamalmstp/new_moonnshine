<?php
declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\Pelatihan; // Pastikan model Pelatihan sudah ada
use MoonShine\Laravel\Pages\Page;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Heading;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\Support\Enums\FormMethod;
use MoonShine\UI\Fields\{Text, Select, Date};
use MoonShine\UI\Components\ActionButton; // Import ActionButton
use App\Http\Controllers\ExportController; // Import ExportController
use Carbon\Carbon;

class LaporanPelatihanPage extends Page
{
    public function getTitle(): string
    {
        return 'Laporan Pelatihan Pegawai';
    }

    public function getBreadcrumbs(): array
    {
        return [
            '#' => $this->getTitle()
        ];
    }

    protected function components(): iterable
    {
        $filters = request()->only(['tahun']);

        $pelatihan = Pelatihan::query()
            ->with('pegawai') // Asumsi ada relasi 'pegawai' di model Pelatihan
            ->when($filters['tahun'] ?? null, fn ($q, $val) => $q->where('tahun', $val)) // Filter berdasarkan 'tahun'
            ->latest()
            ->get();

        $exportXlsxUrl = route('moonshine.laporan.pelatihan.export.xlsx', [
            'tahun' => $filters['tahun'] ?? null,
        ]);

        $exportPdfUrl = route('moonshine.laporan.pelatihan.export.pdf', [
            'tahun' => $filters['tahun'] ?? null,
        ]);

        $currentYear = Carbon::now()->year;
        $yearOptions = ['' => 'Semua Tahun'];
        for ($i = 0; $i < 5; $i++) { // Menampilkan 5 tahun terakhir
            $year = $currentYear - $i;
            $yearOptions[$year] = (string) $year;
        }

        return [
            Grid::make([
                Column::make([
                    // Bagian Filter
                    Box::make([
                        Heading::make('Filter Laporan Pelatihan'),
                        FormBuilder::make(
                            action: request()->url(),
                            method: FormMethod::GET,
                            fields: [
                                Select::make('Tahun Pelatihan', 'tahun')
                                    ->options($yearOptions)
                                    ->setValue(request('tahun'))
                                    ->hint('Filter berdasarkan tahun pelatihan.'),
                            ]
                        )->submit('Terapkan Filter')
                        ->buttons([
                            // Tombol Reset Filter
                            ActionButton::make('Reset Filter', request()->url())
                                ->icon('arrow-path')
                                ->primary(),
                            ActionButton::make('Export Data (XLSX)', $exportXlsxUrl)
                                ->icon('document')
                                ->success()
                                ->blank(),
                            ActionButton::make('Export Data (PDF)', $exportPdfUrl)
                                ->icon('document')
                                ->info()
                                ->blank(),
                        ]),
                    ])->customAttributes(['class' => 'mb-4']), // Menambahkan margin bawah

                    // Bagian Tabel Data Pelatihan
                    Box::make([
                        Heading::make('Data Pelatihan Pegawai'), // Judul untuk tabel
                        TableBuilder::make()
                            ->items($pelatihan)
                            ->fields([
                                Text::make('Nama Pegawai', 'pegawai.nama_lengkap'),
                                Text::make('Tema', 'tema'),
                                Text::make('Penyelenggara', 'penyelenggara'),
                                Text::make('Tempat Pelatihan', 'tempat_pelatihan'),
                                Text::make('Tahun', 'tahun'),
                                Date::make('Tgl. Mulai', 'tanggal_mulai'),
                                Date::make('Tgl. Selesai', 'tanggal_selesai'),
                                Text::make('Surat Tugas', 'surat_tugas'),
                                Text::make('Sertifikat', 'sertifikat'),
                            ])
                            ->buttons([]) // Kosongkan array buttons
                    ])->class('shadow-xl rounded-xl p-4'),
                ])->class('max-w-full'),
            ])
        ];
    }
}
