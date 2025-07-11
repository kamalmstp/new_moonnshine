<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Cuti;
use App\Models\Pegawai;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\Modal;
use MoonShine\Support\ListOf;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\UI\Fields\{ID, Text, Date, Textarea, Select};
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;

class CutiResource extends ModelResource
{
    protected string $model = Cuti::class;

    protected string $title = 'Cuti';

    public function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('Pegawai', 'pegawai', resource: PegawaiResource::class, formatted: 'nama_lengkap'),
            Text::make('Jenis Cuti', 'jenis_cuti'),
            Date::make('Tanggal Mulai', 'tanggal_mulai'),
            Date::make('Tanggal Selesai', 'tanggal_selesai'),
            Text::make('Status', 'status'),
        ];
    }

    public function formFields(): iterable
    {
        return [
            Box::make('Form Cuti', [
                BelongsTo::make('Pegawai', 'pegawai', resource: PegawaiResource::class, formatted: 'nama_lengkap'),
                Select::make('Jenis Cuti', 'jenis_cuti')->options([
                    'Tahunan' => 'Cuti Tahunan',
                    'Sakit' => 'Cuti Sakit',
                    'Melahirkan' => 'Cuti Melahirkan',
                    'Lainnya' => 'Cuti Lainnya',
                ])->required(),
                Date::make('Tanggal Mulai', 'tanggal_mulai')->required(),
                Date::make('Tanggal Selesai', 'tanggal_selesai')->required(),
                Text::make('Nomor Surat', 'nomor_surat'),
                Textarea::make('Alasan', 'alasan')->nullable(),
                Select::make('Status', 'status')->options([
                    'diproses' => 'Diproses',
                    'disetujui' => 'Disetujui',
                    'ditolak' => 'Ditolak',
                ])->default('diproses'),
            ])
        ];
    }

    public function detailFields(): iterable
    {
        return [
            ID::make(),
            BelongsTo::make('Pegawai', 'pegawai', resource: PegawaiResource::class, formatted: 'nama_lengkap'),
            Text::make('Jenis Cuti', 'jenis_cuti'),
            Date::make('Tanggal Mulai', 'tanggal_mulai'),
            Date::make('Tanggal Selesai', 'tanggal_selesai'),
            Text::make('Nomor Surat', 'nomor_surat'),
            Textarea::make('Alasan', 'alasan'),
            Text::make('Status', 'status'),
        ];
    }

    protected function indexButtons(): ListOf
    {
        return parent::indexButtons()
            ->add(
                ActionButton::make('Generate Surat', fn($item) => route('cuti.surat', $item))
                    ->icon('document')
                    ->blank()
                    ->canSee(fn($item) => !is_null($item->nomor_surat)) // sembunyikan jika belum ada
            );
    }

    protected function rules(mixed $item): array
    {
        return [
            'pegawai_id' => ['required', 'exists:pegawai,id'],
            'jenis_cuti' => ['required', 'string'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date'],
            'status' => ['required', 'in:diproses,disetujui,ditolak'],
        ];
    }
}