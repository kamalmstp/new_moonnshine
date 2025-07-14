<?php
declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\PerjalananDinas; // Pastikan model PerjalananDinas sudah ada
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

class LaporanPerjalananDinasPage extends Page
{
    public function getTitle(): string
    {
        return 'Laporan Perjalanan Dinas';
    }

    public function getBreadcrumbs(): array
    {
        return [
            '#' => $this->getTitle()
        ];
    }

    protected function components(): iterable
    {
        $filters = request()->only(['jenis_perjalanan', 'status_perjalanan']);

        $perjalananDinas = PerjalananDinas::query()
            ->with('pegawai') // Pastikan relasi 'pegawai' ada di model PerjalananDinas
            ->when($filters['jenis_perjalanan'] ?? null, fn ($q, $val) => $q->where('jenis_perjalanan', $val))
            ->when($filters['status_perjalanan'] ?? null, fn ($q, $val) => $q->where('status_perjalanan', $val))
            ->latest()
            ->get();

        // Siapkan URL untuk export XLSX, sertakan parameter filter saat ini
        $exportXlsxUrl = route('moonshine.laporan.perjalanan_dinas.export.xlsx', [
            'jenis_perjalanan' => $filters['jenis_perjalanan'] ?? null,
            'status_perjalanan' => $filters['status_perjalanan'] ?? null,
        ]);

        // Siapkan URL untuk export PDF, sertakan parameter filter saat ini
        $exportPdfUrl = route('moonshine.laporan.perjalanan_dinas.export.pdf', [
            'jenis_perjalanan' => $filters['jenis_perjalanan'] ?? null,
            'status_perjalanan' => $filters['status_perjalanan'] ?? null,
        ]);

        return [
            Grid::make([
                Column::make([
                    // Bagian Filter
                    Box::make([
                        Heading::make('Filter Laporan Perjalanan Dinas'), // Judul untuk filter
                        FormBuilder::make(
                            action: request()->url(),
                            method: FormMethod::GET,
                            fields: [
                                Select::make('Jenis Perjalanan', 'jenis_perjalanan')->options([
                                    '' => 'Semua Jenis',
                                    'Dinas Luar Kota' => 'Dinas Luar Kota',
                                    'Dinas Dalam Kota' => 'Dinas Dalam Kota',
                                    'Pelatihan' => 'Pelatihan',
                                    'Rapat' => 'Rapat',
                                ])->setValue(request('jenis_perjalanan')), // Menjaga nilai filter
                                Select::make('Status Perjalanan', 'status_perjalanan')->options([
                                    '' => 'Semua Status',
                                    'Diajukan' => 'Diajukan',
                                    'Disetujui' => 'Disetujui',
                                    'Ditolak' => 'Ditolak',
                                    'Selesai' => 'Selesai',
                                ])->setValue(request('status_perjalanan')), // Menjaga nilai filter
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

                    // Bagian Tabel Data Perjalanan Dinas
                    Box::make([
                        Heading::make('Data Perjalanan Dinas'), // Judul untuk tabel
                        TableBuilder::make()
                            ->items($perjalananDinas)
                            ->fields([
                                Text::make('Nama Pegawai', 'pegawai.nama_lengkap'),
                                Text::make('Jenis Perjalanan', 'jenis_perjalanan'),
                                Date::make('Tgl. Berangkat', 'tanggal_berangkat'),
                                Date::make('Tgl. Kembali', 'tanggal_kembali'),
                                Text::make('Tujuan', 'tujuan'),
                                Text::make('Keterangan', 'keterangan'),
                                Text::make('Status', 'status_perjalanan'),
                                Text::make('No. Surat Tugas', 'nomor_surat_tugas'),
                                Date::make('Tgl. Surat Tugas', 'tanggal_surat_tugas'),
                            ])
                            ->buttons([]) // Kosongkan array buttons
                    ])->class('shadow-xl rounded-xl p-4'),
                ])->class('max-w-full'),
            ])
        ];
    }
}
