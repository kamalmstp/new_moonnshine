<?php
declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\SuratKeluar;
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

class LaporanSuratKeluarPage extends Page
{
    public function getTitle(): string
    {
        return 'Laporan Surat Keluar';
    }

    public function getBreadcrumbs(): array
    {
        return [
            '#' => $this->getTitle()
        ];
    }

    protected function components(): iterable
    {
        $filters = request()->only(['tujuan']);

        $suratKeluar = SuratKeluar::query()
            ->when($filters['tujuan'] ?? null, fn ($q, $val) => $q->where('tujuan', 'like', '%' . $val . '%'))
            ->latest()
            ->get();

        $exportXlsxUrl = route('moonshine.laporan.surat_keluar.export.xlsx', [
            'tujuan' => $filters['tujuan'] ?? null,
        ]);

        $exportPdfUrl = route('moonshine.laporan.surat_keluar.export.pdf', [
            'tujuan' => $filters['tujuan'] ?? null,
        ]);

        return [
            Grid::make([
                Column::make([
                    Box::make([
                        Heading::make('Filter Laporan Surat Keluar'),
                        FormBuilder::make(
                            action: request()->url(),
                            method: FormMethod::GET,
                            fields: [
                                Text::make('Tujuan', 'tujuan')
                                    ->setValue(request('tujuan'))
                                    ->hint('Filter berdasarkan tujuan surat.'),
                            ]
                        )->submit('Terapkan Filter')
                        ->buttons([
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

                    Box::make([
                        Heading::make('Data Surat Keluar'),
                        TableBuilder::make()
                            ->items($suratKeluar)
                            ->fields([
                                Text::make('Nomor Surat', 'nomor_surat'),
                                Text::make('Tujuan', 'tujuan'),
                                Text::make('Perihal', 'perihal'),
                                Date::make('Tanggal Surat', 'tanggal_surat'),
                            ])
                            ->buttons([])
                    ])->class('shadow-xl rounded-xl p-4'),
                ])->class('max-w-full'),
            ])
        ];
    }
}
