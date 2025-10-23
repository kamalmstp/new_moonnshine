<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Disposisi;
use App\Models\User;
use App\Enums\StatusEnum;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\{ID, Text, Textarea, Select, Hidden, NoInput, Date};
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Decorations\Flex;
use MoonShine\Enums\PageType;

class DisposisiResource extends ModelResource
{
    protected string $model = Disposisi::class;

    protected string $title = 'Riwayat Disposisi';
    
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            
            BelongsTo::make('No Surat Masuk', 'surat_masuk', resource: SuratMasukResource::class) 
                ->searchable(),

            BelongsTo::make('Tujuan Disposisi', 'pegawai', resource: PegawaiResource::class) 
                ->searchable(),

            Date::make('Tanggal Disposisi', 'tgl_disposisi')
                ->format('d M Y'),
            
            Text::make('Isi Disposisi', 'isi_disposisi'),

            Text::make('Status', 'status')->badge(
                fn($statusValue) => match($statusValue) {
                    'Pending' => 'warning',
                    'Selesai' => 'green',
                    'Ditolak' => 'danger',
                    default => 'blue'
                }
            ),
        ];
    }

    protected function formFields(): iterable
    {
        $suratMasukId = request()->get('surat_masuk_id');
        $showSelectField = is_null($suratMasukId);

        return [
            Box::make('Formulir Disposisi', [

                BelongsTo::make('Surat Masuk', 'surat_masuk', resource: SuratMasukResource::class, formatted: 'nomor_surat')
                    ->searchable()
                    ->canSee(fn() => $showSelectField),
                Hidden::make('surat_masuk_id')
                    ->setValue($suratMasukId)
                    ->canSee(fn() => !is_null($suratMasukId)),
                BelongsTo::make('Tujuan Disposisi', 'pegawai', resource: PegawaiResource::class, formatted: 'nama_lengkap'),
                Date::make('Tanggal Disposisi', 'tgl_disposisi')
                    ->format('Y-m-d'),
                Hidden::make('Status Awal', 'status')
                    ->setValue('Pending'),
                Textarea::make('Instruksi', 'isi_disposisi')
                    ->required(),
            ]),
        ];
    }

    protected function detailFields(): iterable
    {
        return [
            BelongsTo::make('Nomor Surat Masuk', 'surat_masuk', resource: SuratMasukResource::class, formatted: 'nomor_surat'),
            BelongsTo::make('Perihal', 'surat_masuk', resource: SuratMasukResource::class, formatted: 'perihal'),
            BelongsTo::make('Pengirim', 'surat_masuk', resource: SuratMasukResource::class, formatted: 'pengirim'),
            BelongsTo::make('Tujuan Disposisi', 'pegawai', resource: PegawaiResource::class, formatted: 'nama_lengkap'),
            Text::make('Instruksi', 'isi_disposisi'),
            Date::make('Tanggal Disposisi', 'tgl_disposisi')
                ->format('d M Y'),
            Select::make('Status', 'status')
                ->options([
                    'Pending' => 'Pending',
                    'Selesai' => 'Selesai',
                    'Ditolak' => 'Ditolak',
                ]),
        ];
    }

    public function canSee(mixed $item): bool
    {
        $role = auth()->user()->moonshineUserRole->name;
        
        return in_array($role, ['Admin', 'Kepala Sekolah', 'Staff']);
    }
    
    public function canCreate(): bool
    {
        return auth()->user()->moonshineUserRole->name == 'Kepala Sekolah';
    }

    public function canEdit(mixed $item): bool
    {
        return auth()->user()->id == $item->penerima_id;
    }

    protected function rules(mixed $item): array
    {
        return [
            'isi_disposisi' => ['required', 'string'],
        ];
    }
}