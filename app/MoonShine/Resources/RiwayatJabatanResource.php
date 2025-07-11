<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\RiwayatJabatan;
use App\Models\Pegawai;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\UI\Fields\{ID, Text, Date, File};
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
            ID::make()->sortable(),
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
            Box::make([
                BelongsTo::make('Pegawai', 'pegawai', resource: PegawaiResource::class, formatted: 'nama_lengkap'),
                Text::make('Jabatan', 'jabatan')->required(),
                Date::make('TMT Jabatan', 'tmt_jabatan')->nullable(),
                File::make('SK Jabatan', 'sk_jabatan')->dir('sk_jabatan')->disk('public')->nullable(),
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