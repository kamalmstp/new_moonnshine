<?php
declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\SuratMasuk;
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
use MoonShine\UI\Components\ActionButton;
use App\Http\Controllers\ExportController;

class LaporanSuratMasukPage extends Page
{
    public function getTitle(): string
    {
        return 'Laporan Surat Masuk';
    }

    public function getBreadcrumbs(): array
    {
        return [
            '#' => $this->getTitle()
        ];
    }

    protected function components(): iterable
    {
        $filters = request()->only(['pengirim']);

        $suratMasuk = SuratMasuk::query()
            ->when($filters['pengirim'] ?? null, fn ($q, $val) => $q->where('pengirim', 'like', '%' . $val . '%'))
            ->latest()
            ->get();

        $exportXlsxUrl = route('moonshine.laporan.surat_masuk.export.xlsx', [
            'pengirim' => $filters['pengirim'] ?? null,
        ]);

        $exportPdfUrl = route('moonshine.laporan.surat_masuk.export.pdf', [
            'pengirim' => $filters['pengirim'] ?? null,
        ]);

        return [
            Grid::make([
                Column::make([
                    // Bagian Filter
                    Box::make([
                        Heading::make('Filter Laporan Surat Masuk'), // Judul untuk filter
                        FormBuilder::make(
                            action: request()->url(),
                            method: FormMethod::GET,
                            fields: [
                                Text::make('Pengirim', 'pengirim')
                                    ->setValue(request('pengirim'))
                                    ->hint('Filter berdasarkan nama pengirim.'),
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
                    ])->customAttributes(['class' => 'mb-4']),

                    // Bagian Tabel Data Surat Masuk
                    Box::make([
                        Heading::make('Data Surat Masuk'), // Judul untuk tabel
                        TableBuilder::make()
                            ->items($suratMasuk)
                            ->fields([
                                Text::make('Nomor Surat', 'nomor_surat'),
                                Text::make('Pengirim', 'pengirim'),
                                Text::make('Perihal', 'perihal'),
                                Date::make('Tanggal Surat', 'tanggal_surat'),
                                Date::make('Tanggal Diterima', 'tanggal_diterima'),
                            ])
                            ->buttons([]) // Kosongkan array buttons
                    ])->class('shadow-xl rounded-xl p-4'),
                ])->class('max-w-full'),
            ])
        ];
    }
}
