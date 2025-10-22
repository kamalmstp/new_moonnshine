<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Disposisi;
use App\Models\User;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\{ID, Text, Textarea, Select, Hidden, NoInput, Date};
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Decorations\Flex;
use MoonShine\Enums\PageType;

/**
 * @extends ModelResource<Disposisi>
 */
class DisposisiResource extends ModelResource
{
    protected string $model = Disposisi::class;

    protected string $title = 'Riwayat Disposisi';
    
    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            
            BelongsTo::make('Surat Masuk', 'surat_masuk', resource: SuratMasukResource::class) 
                ->searchable(),

            Text::make('Status', 'status')->badge(
                fn(Disposisi $item) => match($item->status) {
                    'Pending' => 'red',
                    'Selesai' => 'green',
                    default => 'blue'
                }
            ),

            Date::make('Tanggal Disposisi', 'created_at')
                ->format('d/m/Y H:i')
                ->sortable(),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            // Menggunakan Box untuk formulir input modal
            Box::make('Formulir Disposisi', [
                Hidden::make('Surat Masuk ID', 'surat_masuk_id'),
                
                Textarea::make('Instruksi', 'isi_disposisi')
                    ->required(),
            ]),
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
            // Menggunakan Box sebagai container utama
            Box::make('Detail Disposisi & Respons', [
                Flex::make([
                    // Menggunakan Box untuk kolom kiri
                    Box::make('Instruksi Kepala Sekolah', [
                        NoInput::make('Dari', 'pengirim', resource: new UserResource())
                            ->select('name'),
                            
                        NoInput::make('Instruksi', 'isi_disposisi'),

                        NoInput::make('Tanggal Dibuat', 'created_at')
                            ->format('d M Y H:i'),
                    ])->columnSpan(6),

                    // Menggunakan Box untuk kolom kanan
                    Box::make('Tindak Lanjut Staff TU', [
                        Select::make('Ubah Status', 'status')
                            ->options([
                                'Pending' => 'Pending',
                                'Selesai' => 'Selesai',
                                'Ditolak' => 'Ditolak',
                            ])
                            ->canSee(fn(Disposisi $item) => auth()->user()->id == $item->penerima_id),

                        Textarea::make('Catatan Respons', 'catatan_respons')
                            ->placeholder('Tuliskan tindak lanjut atau alasan penolakan.')
                            ->canSee(fn(Disposisi $item) => auth()->user()->id == $item->penerima_id),
                        
                        // Tampilan respons read-only untuk non-penerima
                        NoInput::make('Catatan Respons', 'catatan_respons')
                             ->canSee(fn(Disposisi $item) => auth()->user()->id != $item->penerima_id)
                             ->hideOnForm(),

                    ])->columnSpan(6),
                ])
            ])
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

    /**
     * @param Disposisi $item
     *
     * @return array<string, string[]|string>
     */
    protected function rules(mixed $item): array
    {
        return [
            'penerima_id' => ['required', 'exists:users,id'],
            'isi_disposisi' => ['nullable', 'string'],
            'status' => ['required', 'in:Pending,Selesai,Ditolak'],
        ];
    }
}