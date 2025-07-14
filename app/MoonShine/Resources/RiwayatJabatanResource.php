<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\RiwayatJabatan;
use App\Models\Pegawai;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\UI\Fields\{ID, Text, Date, Select, File};
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;

/**
 * @extends ModelResource<RiwayatJabatan>
 */
class RiwayatJabatanResource extends ModelResource
{
    protected string $model = RiwayatJabatan::class;

    protected string $title = 'Riwayat Jabatan';

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            BelongsTo::make('Pegawai', 'pegawai', resource: PegawaiResource::class, formatted: 'nama_lengkap'),
            Text::make('Jabatan', 'jabatan'),
            Date::make('TMT Jabatan', 'tmt_jabatan'),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            Box::make('Detail Riwayat Jabatan', [
                BelongsTo::make('Pegawai', 'pegawai', resource: PegawaiResource::class, formatted: 'nama_lengkap')
                    ->required()
                    ->searchable(),
                Select::make('Jabatan', 'jabatan')
                    ->options([
                        '-' => '-',
                        'Kepala Sekolah' => 'Kepala Sekolah',
                        'Wakil Kepala Sekolah' => 'Wakil Kepala Sekolah',
                        'Guru Mata Pelajaran' => 'Guru Mata Pelajaran',
                        'Guru BK' => 'Guru BK',
                        'Staf Tata Usaha' => 'Staf Tata Usaha',
                        'Operator Sekolah' => 'Operator Sekolah',
                        'Pustakawan' => 'Pustakawan',
                        'Penjaga Sekolah' => 'Penjaga Sekolah',
                    ])
                    ->required()
                    ->hint('Pilih jabatan yang diemban.'),
                Date::make('TMT Jabatan', 'tmt_jabatan')
                    ->nullable()
                    ->format('Y-m-d')
                    ->hint('Tanggal Mulai Terhitung (TMT) jabatan.'),
                File::make('SK Jabatan', 'sk_jabatan')
                    ->dir('sk_jabatan')
                    ->disk('public')
                    ->nullable()
                    ->removable()
                    ->allowedExtensions(['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'])
                    ->hint('Unggah file Surat Keputusan (SK) Jabatan.'),
            ])
        ];
    }

    protected function detailFields(): iterable
    {
        return [
            BelongsTo::make('Pegawai', 'pegawai', formatted: 'nama_lengkap'),
            Text::make('Jabatan', 'jabatan'),
            Date::make('TMT Jabatan', 'tmt_jabatan')->format('d M Y'),
            File::make('SK Jabatan', 'sk_jabatan')->disk('public')->dir('sk_jabatan'),
        ];
    }

    /**
     * @param RiwayatJabatan $item
     *
     * @return array<string, string[]|string>
     */
    protected function rules(mixed $item): array
    {
        return [
            'pegawai_id' => ['required', 'exists:pegawai,id'],
            'jabatan' => ['required', 'string'],
            'tmt_jabatan' => ['nullable', 'date'],
            'sk_jabatan' => ['nullable', 'file'],
        ];
    }
}