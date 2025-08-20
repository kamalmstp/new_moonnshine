<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Mutasi;
use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\{ID, Text, Date, File, Enum};
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\ActionButton;

class MutasiResource extends ModelResource
{
    protected string $model = Mutasi::class;

    protected string $title = 'Mutasi';

    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('Pegawai', 'pegawai', resource: PegawaiResource::class, formatted: 'nama_lengkap'),
            Text::make('Jenis Mutasi', 'jenis_mutasi'),
            Date::make('Tanggal Mutasi', 'tanggal_mutasi'),
        ];
    }

    protected function formFields(): iterable
    {
        return [
            Box::make('Data Mutasi', [
                BelongsTo::make('Pegawai', 'pegawai', resource: PegawaiResource::class, formatted: 'nama_lengkap')->required(),
                Enum::make('Jenis Mutasi', 'jenis_mutasi')->options([
                    'Internal' => 'Internal',
                    'Eksternal' => 'Eksternal',
                ]),
                Date::make('Tanggal Mutasi', 'tanggal_mutasi'),
                Text::make('Keterangan', 'keterangan')->nullable(),
                File::make('SK Mutasi', 'sk_mutasi')->dir('mutasi/sk')->disk('public')->nullable(),
                Text::make('Nomor Surat', 'nomor_surat')->nullable(),
            ])
        ];
    }

    protected function detailFields(): iterable
    {
        return [
            BelongsTo::make('Pegawai', 'pegawai', resource: PegawaiResource::class, formatted: 'nama_lengkap'),
            Text::make('Jenis Mutasi', 'jenis_mutasi'),
            Date::make('Tanggal Mutasi', 'tanggal_mutasi'),
            Text::make('Keterangan', 'keterangan'),
            File::make('SK Mutasi', 'sk_mutasi')->dir('mutasi/sk')->disk('public'),
            Text::make('Nomor Surat', 'nomor_surat'),
        ];
    }

    protected function rules(mixed $item): array
    {
        return [
            'pegawai_id' => ['required'],
            'jenis_mutasi' => ['required'],
        ];
    }

    protected function indexButtons(): ListOf
    {
        return parent::indexButtons()
            ->add(
                ActionButton::make('Generate Surat', fn($item) => route('mutasi.surat', $item))
                    ->icon('document')
                    ->blank()
            );
    }

    protected function detailButtons(): ListOf
    {
        return parent::detailButtons()
            ->add(
                ActionButton::make('Generate Surat', fn($item) => route('mutasi.surat', $item))
                    ->icon('document')
                    ->blank()
            );
    }
}