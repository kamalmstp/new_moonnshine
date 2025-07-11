<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\RiwayatPangkat;
use App\Models\Pegawai;
use App\Models\PangkatGolongan;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\UI\Fields\{ID, Date, File};
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;

/**
 * @extends ModelResource<RiwayatPangkat>
 */
class RiwayatPangkatResource extends ModelResource
{
    protected string $model = RiwayatPangkat::class;

    protected string $title = 'Riwayat Pangkat';

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('Pegawai', 'pegawai', resource: PegawaiResource::class, formatted: 'nama_lengkap'),
            BelongsTo::make('Pangkat / Golongan', 'pangkatGolongan', resource: PangkatGolonganResource::class, formatted: 'pangkat_golongan')->nullable(),
            Date::make('TMT Pangkat', 'tmt_pangkat'),
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
                BelongsTo::make('Pangkat / Golongan', 'pangkatGolongan', resource: PangkatGolonganResource::class, formatted: 'pangkat_golongan')->nullable(),
                Date::make('TMT Pangkat', 'tmt_pangkat')->nullable(),
                File::make('SK Pangkat', 'sk_pangkat')->dir('sk_pangkat')->disk('public')->nullable(),
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
     * @param RiwayatPangkat $item
     *
     * @return array<string, string[]|string>
     */
    protected function rules(mixed $item): array
    {
        return [
            'pegawai_id' => ['required', 'exists:pegawai,id'],
            'pangkat_golongan_id' => ['nullable', 'exists:pangkat_golongan,id'],
            'tmt_pangkat' => ['nullable', 'date'],
            'sk_pangkat' => ['nullable', 'file'],
        ];
    }
}