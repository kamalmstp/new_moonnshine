<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\PerjalananDinas;
use App\Models\Pegawai;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\File;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\ActionButton;

class PerjalananDinasResource extends ModelResource
{
    protected string $model = PerjalananDinas::class;

    protected string $title = 'Perjalanan Dinas';

    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('Pegawai', 'pegawai', resource: PegawaiResource::class, formatted: 'nama_lengkap'),
            Text::make('Tujuan', 'tujuan'),
            Date::make('Tanggal Berangkat', 'tanggal_berangkat'),
            Date::make('Tanggal Kembali', 'tanggal_kembali'),
            Text::make('Keterangan', 'keterangan')->nullable(),
        ];
    }

    protected function formFields(): iterable
    {
        return [
            BelongsTo::make('Pegawai', 'pegawai', resource: PegawaiResource::class, formatted: 'nama_lengkap')->required(),
            Text::make('Tujuan', 'tujuan')->required(),
            Date::make('Tanggal Berangkat', 'tanggal_berangkat')->required(),
            Date::make('Tanggal Kembali', 'tanggal_kembali')->required(),
            Text::make('Keterangan', 'keterangan')->nullable(),
            File::make('Surat Tugas', 'surat_tugas')
                ->dir('surat-perjalanan')
                ->allowedExtensions(['pdf', 'doc', 'docx'])
                ->removable(),
        ];
    }

    protected function detailFields(): iterable
    {
        return $this->formFields();
    }

    protected function indexButtons(): ListOf
    {
        return parent::indexButtons()
            ->add(
                ActionButton::make('Generate Surat', fn($item) => route('perjalanan_dinas.surat', $item))
                    ->icon('document')
                    ->blank()
            );
    }

    protected function rules(mixed $item): array
    {
        return [
            'pegawai_id' => ['required', 'exists:pegawai,id'],
            'tujuan' => ['required', 'string'],
            'tanggal_berangkat' => ['required', 'date'],
            'tanggal_kembali' => ['required', 'date'],
        ];
    }
}