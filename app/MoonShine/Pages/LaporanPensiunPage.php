<?php
declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\Pensiun;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Heading;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\Support\Enums\FormMethod;
use MoonShine\UI\Fields\{Text, Select, Date, Boolean};
use MoonShine\UI\Components\ActionButton;
use App\Http\Controllers\ExportController;

class LaporanPensiunPage extends Page
{
    public function getTitle(): string
    {
        return 'Laporan Pensiun Pegawai';
    }

    public function getBreadcrumbs(): array
    {
        return [
            '#' => $this->getTitle()
        ];
    }

    protected function components(): iterable
    {
        $filters = request()->only(['jenis_pensiun', 'status_pengajuan']);

        $pensiun = Pensiun::query()
            ->with('pegawai')
            ->when($filters['jenis_pensiun'] ?? null, fn ($q, $val) => $q->where('jenis_pensiun', $val))
            ->when($filters['status_pengajuan'] ?? null, fn ($q, $val) => $q->where('status_pengajuan', $val))
            ->latest()
            ->get();

       
        $exportXlsxUrl = route('moonshine.laporan.pensiun.export.xlsx', [
            'jenis_pensiun' => $filters['jenis_pensiun'] ?? null,
            'status_pengajuan' => $filters['status_pengajuan'] ?? null,
        ]);

       
        $exportPdfUrl = route('moonshine.laporan.pensiun.export.pdf', [
            'jenis_pensiun' => $filters['jenis_pensiun'] ?? null,
            'status_pengajuan' => $filters['status_pengajuan'] ?? null,
        ]);

        return [
            Grid::make([
                Column::make([
                   
                    Box::make([
                        Heading::make('Filter Laporan Pensiun'),
                        FormBuilder::make(
                            action: request()->url(),
                            method: FormMethod::GET,
                            fields: [
                                Select::make('Jenis Pensiun', 'jenis_pensiun')->options([
                                    '' => 'Semua Jenis',
                                    'BUP' => 'BUP',
                                    'Permintaan Sendiri' => 'Permintaan Sendiri',
                                ])->setValue(request('jenis_pensiun')),
                                Select::make('Status Pengajuan', 'status_pengajuan')->options([
                                    '' => 'Semua Status',
                                    'Menunggu' => 'Menunggu',
                                    'Diproses' => 'Diproses',
                                    'Disetujui' => 'Disetujui',
                                    'Ditolak' => 'Ditolak',
                                ])->setValue(request('status_pengajuan')),
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
                        Heading::make('Data Pensiun Pegawai'),
                        TableBuilder::make()
                            ->items($pensiun)
                            ->fields([
                                Text::make('Nama Pegawai', 'pegawai.nama_lengkap'),
                                Text::make('Jenis Pensiun', 'jenis_pensiun'),
                                Date::make('Tanggal Usulan', 'tanggal_usulan'),
                                Text::make('Status Pengajuan', 'status_pengajuan'),
                                Text::make('Nomor Surat', 'nomor_surat'),
                                Text::make('Keterangan', 'keterangan'),
                            ])
                            ->buttons([])
                    ])->class('shadow-xl rounded-xl p-4'),
                ])->class('max-w-full'),
            ])
        ];
    }
}
