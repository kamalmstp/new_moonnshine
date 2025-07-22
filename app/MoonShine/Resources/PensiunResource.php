<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Pensiun;
use App\Models\Pegawai;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\{ID, Date, Select, Text, Textarea, File};
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\ActionButton;

/**
 * @extends ModelResource<Pensiun>
 */
class PensiunResource extends ModelResource
{
    protected string $model = Pensiun::class;

    protected string $title = 'Pensiun';

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            
            BelongsTo::make('Pegawai', 'pegawai', resource: PegawaiResource::class, formatted: 'nama_lengkap'),
            Text::make('Jenis Pensiun', 'jenis_pensiun'),
            Date::make('Tanggal Usulan', 'tanggal_usulan'),
            Date::make('Tanggal Pensiun', 'tanggal_pensiun'),
            Text::make('Status Pengajuan', 'status_pengajuan'),
            Text::make('Nomor Surat', 'nomor_surat'),
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
                    ->required(),
                Select::make('Jenis Pensiun', 'jenis_pensiun')
                    ->options([
                        'BUP' => 'Batas Usia Pensiun',
                        'Permintaan Sendiri' => 'Permintaan Sendiri',
                    ])
                    ->required(),
                Date::make('Tanggal Usulan', 'tanggal_usulan')->required(),
                Date::make('Tanggal Pensiun', 'tanggal_pensiun')->nullable(),
                Select::make('Status Pengajuan', 'status_pengajuan')
                    ->options([
                        'diproses' => 'Diproses',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                    ])
                    ->required(),
                Text::make('Nomor Surat', 'nomor_surat')->nullable(),
                File::make('Surat Keterangan', 'sk_pensiun')->dir('sk_pensiun')->disk('public')->nullable(),
                Textarea::make('Keterangan', 'keterangan')->nullable(),
            ])
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
            BelongsTo::make('Pegawai', 'pegawai', resource: PegawaiResource::class, formatted: 'nama_lengkap')
                    ->required(),
                Select::make('Jenis Pensiun', 'jenis_pensiun')
                    ->options([
                        'BUP' => 'Batas Usia Pensiun',
                        'Permintaan Sendiri' => 'Permintaan Sendiri',
                    ])
                    ->required(),
                Date::make('Tanggal Usulan', 'tanggal_usulan')->required(),
                Date::make('Tanggal Pensiun', 'tanggal_pensiun')->nullable(),
                Select::make('Status Pengajuan', 'status_pengajuan')
                    ->options([
                        'diproses' => 'Diproses',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                    ])
                    ->required(),
                Text::make('Nomor Surat', 'nomor_surat')->nullable(),
                File::make('Surat Keterangan', 'sk_pensiun')->dir('sk_pensiun')->disk('public')->nullable(),
                Textarea::make('Keterangan', 'keterangan')->nullable(),
        ];
    }

    protected function indexButtons(): ListOf
    {
        return parent::indexButtons()
            ->add(
                ActionButton::make('Generate Surat', fn($item) => route('pensiun.surat', $item))
                    ->icon('document')
                    ->blank()
                    ->canSee(fn($item) => !is_null($item->nomor_surat))
            );
    }

    /**
     * @param Pensiun $item
     *
     * @return array<string, string[]|string>
     */
    protected function rules(mixed $item): array
    {
        return [
            'pegawai_id' => ['required', 'exists:pegawai,id'],
            'jenis_pensiun' => ['required'],
            'tanggal_usulan' => ['required', 'date'],
            'status_pengajuan' => ['required'],
        ];
    }
}