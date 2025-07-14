<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use MoonShine\Laravel\Pages\Page;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Heading;
use MoonShine\UI\Components\Metrics\Wrapped\ValueMetric;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Fields\Text; // Digunakan untuk mendefinisikan kolom pada TableBuilder

// Import Models yang dibutuhkan
use App\Models\Pegawai;
use App\Models\Cuti;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;

// Pastikan ini ada jika Anda ingin melewatkan menu di sidebar secara otomatis
#[\MoonShine\MenuManager\Attributes\SkipMenu]

class Dashboard extends Page
{
    /**
     * Mengatur breadcrumbs untuk halaman dashboard.
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
     * Mengatur judul halaman dashboard.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title ?: 'Dashboard';
    }

    /**
     * Mendefinisikan komponen-komponen UI yang akan ditampilkan di dashboard.
     *
     * @return list<ComponentContract>
     */
    protected function components(): iterable
    {
        $totalPegawai = Pegawai::count();
        $totalCuti = Cuti::count();
        $totalSuratMasuk = SuratMasuk::count();
        $totalSuratKeluar = SuratKeluar::count();

        $cutiPending = Cuti::where('status', 'diproses')->count();
        $recentCuti = Cuti::with('pegawai')->orderBy('created_at', 'desc')->limit(5)->get();

        $recentSuratMasuk = SuratMasuk::orderBy('created_at', 'desc')->limit(5)->get();

        return [
            Grid::make([

                Column::make([
                    Heading::make('Ringkasan Data Kepegawaian'),
                    Grid::make([
                        Column::make([
                            Box::make([
                                ValueMetric::make('Total Pegawai')
                                    ->value($totalPegawai)
                                    ->icon('users'),
                            ])
                        ])->columnSpan(6), 

                        Column::make([
                            Box::make([
                                ValueMetric::make('Total Pengajuan Cuti')
                                    ->value($totalCuti)
                                    ->icon('calendar-days'),
                            ])
                        ])->columnSpan(6),

                        Column::make([
                            Box::make([
                                ValueMetric::make('Cuti Menunggu Persetujuan')
                                    ->value($cutiPending)
                                    ->icon('clock'),
                            ])
                        ])->columnSpan(6),

                        Column::make([
                            Box::make([
                                ValueMetric::make('Total Surat Masuk')
                                    ->value($totalSuratMasuk)
                                    ->icon('inbox'),
                            ])
                        ])->columnSpan(6),

                        Column::make([
                            Box::make([
                                ValueMetric::make('Total Surat Keluar')
                                    ->value($totalSuratKeluar)
                                    ->icon('paper-airplane'),
                            ])
                        ])->columnSpan(6),

                    ])
                ])->columnSpan(8),

                Column::make([
                    Heading::make('Aktivitas Terbaru'),

                    Box::make([
                        Heading::make('5 Pengajuan Cuti Terbaru'),
                        TableBuilder::make()
                            ->items($recentCuti)
                            ->fields([
                                Text::make('Pegawai', 'pegawai.nama_lengkap'),
                                Text::make('Jenis Cuti', 'jenis_cuti'),
                                Text::make('Tgl. Mulai', 'tanggal_mulai'),
                                Text::make('Status', 'status'),
                            ]),
                    ])->customAttributes(['class' => 'mb-4']),

                    Box::make([
                        Heading::make('5 Surat Masuk Terbaru'),
                        TableBuilder::make(items: $recentSuratMasuk, fields: [
                            Text::make('Nomor Surat', 'nomor_surat'),
                            Text::make('Pengirim', 'pengirim'),
                            Text::make('Perihal', 'perihal'),
                            Text::make('Tgl. Surat', 'tanggal_surat'),
                        ]),
                    ])
                ])->columnSpan(4),
            ])
        ];
    }
}
