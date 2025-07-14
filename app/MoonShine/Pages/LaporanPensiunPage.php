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

        return [
            Grid::make([
                Column::make([
                    Heading::make('Laporan Pensiun Pegawai'),

                    FormBuilder::make(
                        action: request()->url(),
                        method: FormMethod::GET,
                        fields: [
                            Select::make('Jenis Pensiun', 'jenis_pensiun')->options([
                                '' => 'Semua',
                                'BUP' => 'BUP',
                                'Permintaan Sendiri' => 'Permintaan Sendiri',
                            ]),
                            Select::make('Status Pengajuan', 'status_pengajuan')->options([
                                '' => 'Semua',
                                'Menunggu' => 'Menunggu',
                                'Diproses' => 'Diproses',
                                'Disetujui' => 'Disetujui',
                                'Ditolak' => 'Ditolak',
                            ]),
                        ]
                    )->submit('Filter')->class('mb-4'),

                    Box::make([
                        TableBuilder::make()
                            ->items($pensiun)
                            ->fields([
                                Text::make('Nama Pegawai', 'pegawai.nama_lengkap'),
                                Text::make('Jenis Pensiun', 'jenis_pensiun'),
                                Date::make('Tanggal Usulan', 'tanggal_usulan'),
                                Text::make('Status Pengajuan', 'status_pengajuan'),
                                Text::make('Keterangan', 'keterangan'),
                            ])
                    ])->class('shadow-xl rounded-xl p-4'),
                ]),
            ]),
        ];
    }
}