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
            ->with('pegawai')
            ->when($filters['jenis_mutasi'] ?? null, fn ($q, $val) => $q->where('jenis_mutasi', $val))
            ->latest()
            ->get();

        return [
            Grid::make([
                Column::make([
                    FormBuilder::make(
                        action: request()->url(),
                        method: FormMethod::GET,
                        fields: [
                            Select::make('Jenis Mutasi', 'jenis_mutasi')->options([
                                '' => 'Semua',
                                'internal' => 'Internal',
                                'eksternal' => 'Eksternal',
                            ]),
                        ]
                    )->submit('Filter')->class('mb-4'),

                    Box::make([
                        TableBuilder::make()
                            ->items($mutasi)
                            ->fields([
                                Text::make('Nama Pegawai', 'pegawai.nama_lengkap'),
                                Text::make('Jenis Mutasi', 'jenis_mutasi'),
                                Date::make('Tanggal Mutasi', 'tanggal_mutasi'),
                                Text::make('Keterangan', 'keterangan'),
                                Text::make('SK Mutasi', 'sk_mutasi'),
                            ])
                    ])->class('shadow-xl rounded-xl p-4'),
                ]),
            ]),
        ];
    }
}