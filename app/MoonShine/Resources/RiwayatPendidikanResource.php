<?php
declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\RiwayatPendidikan;
use App\Models\Pegawai;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\{ID, Text, Enum, File};
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;

/**
 * @extends ModelResource<RiwayatPendidikan>
 */
class RiwayatPendidikanResource extends ModelResource
{
    protected string $model = RiwayatPendidikan::class;
    protected string $title = 'Riwayat Pendidikan';

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            BelongsTo::make('Pegawai', 'pegawai', resource: PegawaiResource::class, formatted: 'nama_lengkap'),
            Text::make('Tingkat', 'tingkat'),
            Text::make('Instansi', 'instansi'),
            Text::make('Program Studi', 'program_studi'),
            Text::make('Tahun Lulus', 'tahun_lulus'),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            Box::make([
                BelongsTo::make('Pegawai', 'pegawai', resource: PegawaiResource::class, formatted: 'nama_lengkap')
                    ->required()
                    ->searchable(),

                Enum::make('Tingkat', 'tingkat')
                    ->options([
                        '-' => '-',
                        'SD' => 'SD',
                        'SMP' => 'SMP',
                        'SMA' => 'SMA',
                        'D1' => 'D1',
                        'D2' => 'D2',
                        'D3' => 'D3',
                        'S1' => 'S1',
                        'S2' => 'S2',
                        'S3' => 'S3',
                    ])
                    ->required(),
                Text::make('Program Studi', 'program_studi')->nullable(),
                Text::make('Instansi', 'instansi')->required(),
                Text::make('Tahun Lulus', 'tahun_lulus')->required(),
                File::make('Ijazah', 'ijazah')->disk('public')->dir('pegawai/ijazah')->nullable(),
            ])
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
            Text::make('Pegawai', 'pegawai.nama_lengkap'),
            Text::make('Tingkat', 'tingkat'),
            Text::make('Instansi', 'instansi'),
            Text::make('Program Studi', 'program_studi'),
            Text::make('Tahun Lulus', 'tahun_lulus'),
        ];
    }

    protected function rules(mixed $item): array
    {
        return [
            'tingkat' => ['required', 'string'],
            'instansi' => ['required', 'string'],
            'tahun_lulus' => ['required', 'string'],
        ];
    }
}