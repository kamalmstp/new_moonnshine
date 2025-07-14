<?php
declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\Mutasi;
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

class LaporanMutasiPage extends Page
{
    public function getTitle(): string
    {
        return 'Laporan Mutasi Pegawai';
    }

    public function getBreadcrumbs(): array
    {
        return [
            '#' => $this->getTitle()
        ];
    }

    protected function components(): iterable
    {
        $filters = request()->only(['jenis_mutasi']);

        $mutasi = Mutasi::query()
            ->with('pegawai') // Pastikan relasi 'pegawai' ada di model Mutasi
            ->when($filters['jenis_mutasi'] ?? null, fn ($q, $val) => $q->where('jenis_mutasi', $val))
            ->latest()
            ->get();

        // Siapkan URL untuk export XLSX, sertakan parameter filter saat ini
        $exportXlsxUrl = route('moonshine.laporan.mutasi.export.xlsx', [
            'jenis_mutasi' => $filters['jenis_mutasi'] ?? null,
        ]);

        // Siapkan URL untuk export PDF, sertakan parameter filter saat ini
        $exportPdfUrl = route('moonshine.laporan.mutasi.export.pdf', [
            'jenis_mutasi' => $filters['jenis_mutasi'] ?? null,
        ]);

        return [
            Grid::make([
                Column::make([
                    // Bagian Filter
                    Box::make([
                        Heading::make('Filter Laporan Mutasi'), // Judul untuk filter
                        FormBuilder::make(
                            action: request()->url(),
                            method: FormMethod::GET,
                            fields: [
                                Select::make('Jenis Mutasi', 'jenis_mutasi')->options([
                                    '' => 'Semua Jenis', // Tambahkan opsi 'Semua Jenis'
                                    'internal' => 'Internal',
                                    'eksternal' => 'Eksternal',
                                ])->setValue(request('jenis_mutasi')), // Menjaga nilai filter
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

                    // Bagian Tabel Data Mutasi
                    Box::make([
                        Heading::make('Data Mutasi Pegawai'), // Judul untuk tabel
                        TableBuilder::make()
                            ->items($mutasi)
                            ->fields([
                                Text::make('Nama Pegawai', 'pegawai.nama_lengkap'),
                                Text::make('Jenis Mutasi', 'jenis_mutasi'),
                                Date::make('Tanggal Mutasi', 'tanggal_mutasi'),
                                Text::make('Keterangan', 'keterangan'),
                                Text::make('SK Mutasi', 'sk_mutasi'),
                                Text::make('Nomor Surat', 'nomor_surat'), // Tambahkan kolom nomor surat
                                Date::make('Tanggal Surat', 'tanggal_surat'), // Tambahkan kolom tanggal surat
                            ])
                            ->buttons([]) // Kosongkan array buttons
                    ])->class('shadow-xl rounded-xl p-4'),
                ])->class('max-w-full'),
            ])
        ];
    }
}
