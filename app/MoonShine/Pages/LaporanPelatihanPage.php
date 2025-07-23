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
        $filters = request()->only(['jenis_pelatihan', 'status_pelaksanaan']);

        $pelatihan = Pelatihan::query()
            ->with('pegawai') // Pastikan relasi 'pegawai' ada di model Pelatihan
            ->when($filters['jenis_pelatihan'] ?? null, fn ($q, $val) => $q->where('jenis_pelatihan', $val))
            ->when($filters['status_pelaksanaan'] ?? null, fn ($q, $val) => $q->where('status_pelaksanaan', $val))
            ->latest()
            ->get();

        // Siapkan URL untuk export XLSX, sertakan parameter filter saat ini
        $exportXlsxUrl = route('moonshine.laporan.pelatihan.export.xlsx', [
            'jenis_pelatihan' => $filters['jenis_pelatihan'] ?? null,
            'status_pelaksanaan' => $filters['status_pelaksanaan'] ?? null,
        ]);

        // Siapkan URL untuk export PDF, sertakan parameter filter saat ini
        $exportPdfUrl = route('moonshine.laporan.pelatihan.export.pdf', [
            'jenis_pelatihan' => $filters['jenis_pelatihan'] ?? null,
            'status_pelaksanaan' => $filters['status_pelaksanaan'] ?? null,
        ]);

        return [
            Grid::make([
                Column::make([
                    // Bagian Filter
                    Box::make([
                        Heading::make('Filter Laporan Pelatihan'), // Judul untuk filter
                        FormBuilder::make(
                            action: request()->url(),
                            method: FormMethod::GET,
                            fields: [
                                Select::make('Jenis Pelatihan', 'jenis_pelatihan')->options([
                                    '' => 'Semua Jenis',
                                    'Workshop' => 'Workshop',
                                    'Seminar' => 'Seminar',
                                    'Kursus' => 'Kursus',
                                    'Bimtek' => 'Bimtek',
                                    'Lainnya' => 'Lainnya',
                                ])->setValue(request('jenis_pelatihan')), // Menjaga nilai filter
                                Select::make('Status Pelaksanaan', 'status_pelaksanaan')->options([
                                    '' => 'Semua Status',
                                    'Terjadwal' => 'Terjadwal',
                                    'Sedang Berlangsung' => 'Sedang Berlangsung',
                                    'Selesai' => 'Selesai',
                                    'Dibatalkan' => 'Dibatalkan',
                                ])->setValue(request('status_pelaksanaan')), // Menjaga nilai filter
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
                                Text::make('Nama Pelatihan', 'nama_pelatihan'),
                                Text::make('Tema', 'tema'), // Kolom baru
                                Text::make('Jenis Pelatihan', 'jenis_pelatihan'),
                                Text::make('Penyelenggara', 'penyelenggara'),
                                Text::make('Tempat Pelatihan', 'tempat_pelatihan'), // Kolom baru
                                Text::make('Tahun', 'tahun'), // Kolom baru
                                Date::make('Tgl. Mulai', 'tanggal_mulai'),
                                Date::make('Tgl. Selesai', 'tanggal_selesai'),
                                Text::make('Surat Tugas', 'surat_tugas'), // Kolom baru
                                Text::make('Sertifikat', 'sertifikat'),
                            ])
                            ->buttons([]) // Kosongkan array buttons
                    ])->class('shadow-xl rounded-xl p-4'),
                ])->class('max-w-full'),
            ])
        ];
    }
}
