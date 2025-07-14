<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\Pegawai;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Laravel\Pages\Page;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Components\Heading;
use MoonShine\UI\Fields\{Text, Select};
use MoonShine\UI\Components\FormBuilder;
use Illuminate\Http\Request;
use MoonShine\Support\Enums\FormMethod;
use Illuminate\Support\Collection;
use MoonShine\Components\ExportButton;
use MoonShine\Support\Export\ExportFormat;
// use MoonShine\Actions\FiltersAction; // Tidak diperlukan jika menggunakan ActionButton
use MoonShine\UI\Components\ActionButton; // Menggunakan ActionButton dengan namespace yang benar

class LaporanPegawaiPage extends Page
{
    /**
     * Mengatur judul halaman.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title ?: 'Laporan Data Pegawai';
    }

    /**
     * Mengatur breadcrumbs untuk halaman.
     *
     * @return array<string, string>
     */
    public function getBreadcrumbs(): array
    {
        return [
            '#' => $this->getTitle()
        ];
    }

    /**
     * Mendefinisikan komponen-komponen UI yang akan ditampilkan di halaman.
     *
     * @return list<ComponentContract>
     */
    protected function components(): iterable
    {
        // Ambil filter dari request
        $filters = request()->only(['status_kepegawaian', 'jabatan']);

        // Query data pegawai dengan eager loading relasi
        $pegawai = Pegawai::query()
            ->with(['pangkatGolongan', 'mataPelajaran']) // Eager load relasi untuk menghindari N+1 problem
            ->when($filters['status_kepegawaian'] ?? null, fn ($q, $val) => $q->where('status_kepegawaian', $val))
            ->when($filters['jabatan'] ?? null, fn ($q, $val) => $q->where('jabatan', $val))
            ->orderBy('nama_lengkap')
            ->get();

        // Siapkan URL untuk export XLSX, sertakan parameter filter saat ini
        $exportXlsxUrl = route('moonshine.laporan.pegawai.export.xlsx', [
            'status_kepegawaian' => $filters['status_kepegawaian'] ?? null,
            'jabatan' => $filters['jabatan'] ?? null,
        ]);

        // Siapkan URL untuk export PDF, sertakan parameter filter saat ini
        $exportPdfUrl = route('moonshine.laporan.pegawai.export.pdf', [
            'status_kepegawaian' => $filters['status_kepegawaian'] ?? null,
            'jabatan' => $filters['jabatan'] ?? null,
        ]);

        return [
            Grid::make([
                Column::make([
                    // Bagian Filter
                    Box::make([
                        Heading::make('Filter Laporan Pegawai'),
                        FormBuilder::make(
                            action: request()->url(),
                            method: FormMethod::GET,
                            fields: [
                                Select::make('Status Kepegawaian', 'status_kepegawaian')
                                    ->options([
                                        '' => 'Semua', // Opsi "Semua" untuk mereset filter
                                        'PNS' => 'PNS',
                                        'PPPK' => 'PPPK',
                                        'Honor Provinsi' => 'Honor Provinsi',
                                        'Honor Sekolah' => 'Honor Sekolah',
                                    ])
                                    ->nullable() // Memungkinkan pilihan kosong
                                    ->setValue(request('status_kepegawaian')), // Menjaga nilai filter setelah submit

                                Select::make('Jabatan', 'jabatan')
                                    ->options([
                                        '' => 'Semua', // Opsi "Semua" untuk mereset filter
                                        'Kepala Sekolah' => 'Kepala Sekolah',
                                        'Wakil Kepala' => 'Wakil Kepala',
                                        'Guru' => 'Guru',
                                        'Tata Usaha' => 'Tata Usaha',
                                        'Operator' => 'Operator',
                                        'Satpam' => 'Satpam',
                                    ])
                                    ->nullable() // Memungkinkan pilihan kosong
                                    ->setValue(request('jabatan')), // Menjaga nilai filter setelah submit
                            ]
                        )
                        ->submit('Terapkan Filter') // Tombol untuk menerapkan filter
                        ->buttons([
                            // Tombol untuk mereset filter menggunakan ActionButton
                            ActionButton::make('Reset Filter', request()->url())
                                ->icon('arrow-path')
                                ->primary(),
                            // Tombol Export XLSX menggunakan ActionButton
                            ActionButton::make('Export Data (XLSX)', $exportXlsxUrl)
                                ->icon('document')
                                ->success()
                                ->blank(), // Membuka di tab baru (opsional)
                            // Tombol Export PDF menggunakan ActionButton
                            ActionButton::make('Export Data (PDF)', $exportPdfUrl)
                                ->icon('document') // Icon untuk PDF
                                ->info() // Warna biru untuk tombol PDF
                                ->blank(), // Membuka di tab baru (opsional)
                        ]),
                    ])->customAttributes(['class' => 'mb-4']), // Menambahkan margin bawah

                    // Bagian Tabel dan Export
                    Box::make([
                        Heading::make('Data Pegawai'),
                        TableBuilder::make()
                            ->items($pegawai)
                            ->fields([
                                Text::make('NIP', 'nip'),
                                Text::make('Nama Lengkap', 'nama_lengkap'),
                                Text::make('Jenis Kelamin', 'jenis_kelamin'),
                                Text::make('Status Kepegawaian', 'status_kepegawaian'),
                                Text::make('Jabatan', 'jabatan'),
                                Text::make('Pangkat', 'pangkatGolongan.nama_pangkat'), // Pastikan relasi di model Pegawai
                                Text::make('Golongan', 'pangkatGolongan.golongan'),   // Pastikan relasi di model Pegawai
                                Text::make('Mata Pelajaran', 'mataPelajaran.nama_mapel'), // Pastikan relasi di model Pegawai
                                Text::make('Total Jam Mengajar', 'total_jam_mengajar'),
                            ])
                            ->buttons([])
                    ]),
                ]),
            ]),
        ];
    }
}
