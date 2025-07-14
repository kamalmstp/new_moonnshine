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
            ->with('pegawai')
            ->when($filters['status'] ?? null, fn ($q, $val) => $q->where('status', $val))
            ->when($filters['jenis_cuti'] ?? null, fn ($q, $val) => $q->where('jenis_cuti', $val))
            ->latest()
            ->get();

        return [
            Grid::make([
                Column::make([
                    FormBuilder::make(
                        action: request()->url(),
                        method: FormMethod::GET,
                        fields: [
                            Select::make('Status', 'status')->options([
                                '' => 'Semua Status',
                                'diproses' => 'Diproses',
                                'disetujui' => 'Disetujui',
                                'ditolak' => 'Ditolak',
                            ]),
                            Select::make('Jenis Cuti', 'jenis_cuti')->options([
                                '' => 'Semua Jenis',
                                'Tahunan' => 'Tahunan',
                                'Sakit' => 'Sakit',
                                'Melahirkan' => 'Melahirkan',
                                'Ibadah' => 'Ibadah',
                                'Lainnya' => 'Lainnya',
                            ]),
                        ]
                    )->submit('Terapkan Filter')->class('mb-4'),
                    Box::make([
                        TableBuilder::make()
                            ->items($cuti)
                            ->fields([
                                Text::make('Nama Pegawai', 'pegawai.nama_lengkap'),
                                Text::make('Jenis Cuti', 'jenis_cuti'),
                                Date::make('Tanggal Mulai', 'tanggal_mulai'),
                                Date::make('Tanggal Selesai', 'tanggal_selesai'),
                                Text::make('Status', 'status'),
                            ])
                    ])->class('shadow-xl rounded-xl p-4'),
                ])->class('max-w-full'),
            ])
        ];
    }
}