<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Pelatihan;
use App\Models\Pegawai;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\UI\Fields\{ID, Text, File};
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
            ID::make()->sortable(),
            BelongsTo::make('Pegawai', 'pegawai', resource: PegawaiResource::class, formatted: 'nama_lengkap'),
            Text::make('Nama Pelatihan', 'nama_pelatihan'),
            Text::make('Tahun', 'tahun')->sortable(),
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
                Text::make('Nama Pelatihan', 'nama_pelatihan')->required(),
                Text::make('Penyelenggara', 'penyelenggara')->nullable(),
                Text::make('Tahun', 'tahun')->nullable(),
                File::make('Sertifikat', 'sertifikat')->dir('sertifikat')->disk('public')->nullable(),
            ])
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return $this->indexFields();
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
            'nama_pelatihan' => ['required', 'string'],
            'penyelenggara' => ['nullable', 'string'],
            'tahun' => ['nullable', 'string'],
            'sertifikat' => ['nullable', 'file'],
        ];
    }
}