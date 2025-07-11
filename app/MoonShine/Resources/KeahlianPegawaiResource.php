<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\KeahlianPegawai;
use App\Models\Pegawai;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\{ID, Text};
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;

/**
 * @extends ModelResource<KeahlianPegawai>
 */
class KeahlianPegawaiResource extends ModelResource
{
    protected string $model = KeahlianPegawai::class;

    protected string $title = 'Keahlian Pegawai';

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('Pegawai', 'pegawai', resource: PegawaiResource::class, formatted: 'nama_lengkap'),
            Text::make('Nama Keahlian', 'nama_keahlian'),
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
                Text::make('Nama Keahlian', 'nama_keahlian')->required(),
                Text::make('Keterangan', 'keterangan')->nullable(),
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
     * @param KeahlianPegawai $item
     *
     * @return array<string, string[]|string>
     */
    protected function rules(mixed $item): array
    {
        return [
            'pegawai_id' => ['required', 'exists:pegawai,id'],
            'nama_keahlian' => ['required', 'string'],
            'keterangan' => ['nullable', 'string'],
        ];
    }
}