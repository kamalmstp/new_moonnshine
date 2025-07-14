<?php
declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\Cuti;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Heading;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\Support\Enums\FormMethod;
use MoonShine\UI\Fields\{Text, Date, Select};
use MoonShine\UI\Typography\Paragraph;
use MoonShine\UI\Components\ActionButton; // Import ActionButton
use App\Http\Controllers\ExportController; // Import ExportController

class LaporanCutiPage extends Page
{
    public function getTitle(): string
    {
        return 'Laporan Cuti Pegawai';
    }

    public function getBreadcrumbs(): array
    {
        return [
            '#' => $this->getTitle()
        ];
    }

    protected function components(): iterable
    {
        $filters = request()->only(['status', 'jenis_cuti']);
        $cuti = Cuti::query()
            ->with('pegawai') // Pastikan relasi 'pegawai' ada di model Cuti
            ->when($filters['status'] ?? null, fn ($q, $val) => $q->where('status', $val))
            ->when($filters['jenis_cuti'] ?? null, fn ($q, $val) => $q->where('jenis_cuti', $val))
            ->latest()
            ->get();

        // Siapkan URL untuk export XLSX, sertakan parameter filter saat ini
        $exportXlsxUrl = route('moonshine.laporan.cuti.export.xlsx', [
            'status' => $filters['status'] ?? null,
            'jenis_cuti' => $filters['jenis_cuti'] ?? null,
        ]);

        // Siapkan URL untuk export PDF, sertakan parameter filter saat ini
        $exportPdfUrl = route('moonshine.laporan.cuti.export.pdf', [
            'status' => $filters['status'] ?? null,
            'jenis_cuti' => $filters['jenis_cuti'] ?? null,
        ]);

        return [
            Grid::make([
                Column::make([
                    // Bagian Filter
                    Box::make([
                        Heading::make('Filter Laporan Cuti'), // Judul untuk filter
                        FormBuilder::make(
                            action: request()->url(),
                            method: FormMethod::GET,
                            fields: [
                                Select::make('Status', 'status')->options([
                                    '' => 'Semua Status',
                                    'diproses' => 'Diproses',
                                    'disetujui' => 'Disetujui',
                                    'ditolak' => 'Ditolak',
                                ])->setValue(request('status')), // Menjaga nilai filter
                                Select::make('Jenis Cuti', 'jenis_cuti')->options([
                                    '' => 'Semua Jenis',
                                    'Tahunan' => 'Tahunan',
                                    'Sakit' => 'Sakit',
                                    'Melahirkan' => 'Melahirkan',
                                    'Ibadah' => 'Ibadah',
                                    'Lainnya' => 'Lainnya',
                                ])->setValue(request('jenis_cuti')), // Menjaga nilai filter
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
                                ->blank()
                        ]),
                    ])->customAttributes(['class' => 'mb-4']), 

                    // Bagian Tabel Data Cuti
                    Box::make([
                        Heading::make('Data Pengajuan Cuti'), // Judul untuk tabel
                        TableBuilder::make()
                            ->items($cuti)
                            ->fields([
                                Text::make('Nama Pegawai', 'pegawai.nama_lengkap'),
                                Text::make('Jenis Cuti', 'jenis_cuti'),
                                Date::make('Tanggal Mulai', 'tanggal_mulai'),
                                Date::make('Tanggal Selesai', 'tanggal_selesai'),
                                Text::make('Alasan', 'alasan'), // Tambahkan kolom alasan
                                Text::make('Status', 'status'),
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
