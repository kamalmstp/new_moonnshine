<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Pelatihan;
use App\Models\Pegawai;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\UI\Fields\{ID, Text, Date, File};
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;

/**
 * @extends ModelResource<Pelatihan>
 */
class PelatihanResource extends ModelResource
{
    protected string $model = Pelatihan::class;

    protected string $title = 'Pelatihan';

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            BelongsTo::make('Pegawai', 'pegawai', resource: PegawaiResource::class, formatted: 'nama_lengkap'),
            Text::make('Tema', 'tema'),
            Text::make('Penyelenggara', 'penyelenggara'),
            Date::make('Tanggal Mulai', 'tanggal_mulai')->sortable(),
            Date::make('Tanggal Selesai', 'tanggal_selesai')->sortable(),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            Box::make([
                BelongsTo::make('Pegawai', 'pegawai', resource: PegawaiResource::class, formatted: 'nama_lengkap'),
                Text::make('Tema', 'tema')->required(),
                Text::make('Penyelenggara', 'penyelenggara')->nullable(),
                Text::make('Tempat Pelatihan', 'tempat_pelatihan')->nullable(),
                Date::make('Tanggal Mulai', 'tanggal_mulai')->nullable(),
                Date::make('Tanggal Selesai', 'tanggal_selesai')->nullable(),
                File::make('Surat Tugas', 'surat_tugas')->dir('surat-tugas')->disk('public')->nullable(),
                File::make('Sertifikat', 'sertifikat')->dir('sertifikat')->disk('public')->nullable(),
            ])
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
                BelongsTo::make('Pegawai', 'pegawai', resource: PegawaiResource::class, formatted: 'nama_lengkap'),
                Text::make('Tema', 'tema')->required(),
                Text::make('Penyelenggara', 'penyelenggara')->nullable(),
                Text::make('Tempat Pelatihan', 'tempat_pelatihan')->nullable(),
                Date::make('Tanggal Mulai', 'tanggal_mulai')->nullable(),
                Date::make('Tanggal Selesai', 'tanggal_selesai')->nullable(),
                File::make('Surat Tugas', 'surat_tugas')->dir('surat-tugas')->disk('public')->nullable(),
                File::make('Sertifikat', 'sertifikat')->dir('sertifikat')->disk('public')->nullable(),
        ];
    }

    /**
     * @param Pelatihan $item
     *
     * @return array<string, string[]|string>
     */
    protected function rules(mixed $item): array
    {
        return [
            'pegawai_id' => ['required', 'exists:pegawai,id'],
            'tema' => ['required', 'string'],
            'penyelenggara' => ['nullable', 'string'],
            'sertifikat' => ['nullable', 'file'],
        ];
    }
}