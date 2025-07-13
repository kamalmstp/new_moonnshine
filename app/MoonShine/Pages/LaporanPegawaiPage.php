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
use MoonShine\UI\Fields\{Text, Enum, Select};
use MoonShine\UI\Components\FormBuilder;
use Illuminate\Http\Request;
use MoonShine\Support\Enums\FormMethod;
use Illuminate\Support\Collection;
use MoonShine\Components\ExportButton;
use MoonShine\Support\Export\ExportFormat;

class LaporanPegawaiPage extends Page
{
    public function getTitle(): string
    {
        return $this->title ?: 'Laporan Data Pegawai';
    }

    public function getBreadcrumbs(): array
    {
        return [
            '#' => $this->getTitle()
        ];
    }

    protected function components(): iterable
    {
        $filters = request()->only(['status_kepegawaian', 'jabatan']);

        $pegawai = Pegawai::query()
            ->when($filters['status_kepegawaian'] ?? null, fn ($q, $val) => $q->where('status_kepegawaian', $val))
            ->when($filters['jabatan'] ?? null, fn ($q, $val) => $q->where('jabatan', $val))
            ->orderBy('nama_lengkap')
            ->get();

        return [
            Grid::make([
                Column::make([
                    // FILTER

                    FormBuilder::make(
                        action: request()->url(),
                        method: FormMethod::GET,
                        fields: [
                            Select::make('Status Kepegawaian', 'status_kepegawaian')
                                ->options([
                                    '' => 'Semua',
                                    'PNS' => 'PNS',
                                    'PPPK' => 'PPPK',
                                    'Honor Provinsi' => 'Honor Provinsi',
                                    'Honor Sekolah' => 'Honor Sekolah',
                                ]),
                            Select::make('Jabatan', 'jabatan')
                                ->options([
                                    '' => 'Semua',
                                    'Kepala Sekolah' => 'Kepala Sekolah',
                                    'Wakil Kepala' => 'Wakil Kepala',
                                    'Guru' => 'Guru',
                                    'Tata Usaha' => 'Tata Usaha',
                                    'Operator' => 'Operator',
                                    'Satpam' => 'Satpam',
                                ]),
                        ]
                    )->submit('Filter'),

                    // TABEL DAN EXPORT
                    Box::make([
                        TableBuilder::make()
                            ->items($pegawai)
                            ->fields([
                                Text::make('NIP', 'nip'),
                                Text::make('Nama Lengkap', 'nama_lengkap'),
                                Text::make('Jenis Kelamin', 'jenis_kelamin'),
                                Text::make('Status Kepegawaian', 'status_kepegawaian'),
                                Text::make('Jabatan', 'jabatan'),
                                Text::make('Pangkat', 'pangkatGolongan.nama_pangkat'),
                                Text::make('Golongan', 'pangkatGolongan.golongan'),
                                Text::make('Mata Pelajaran', 'mataPelajaran.nama_mapel'),
                                Text::make('Total Jam Mengajar', 'total_jam_mengajar'),
                            ])
                            ->buttons([
                                
                            ])
                    ]),
                ]),
            ]),
        ];
    }
}