<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\SuratMasuk;
use Illuminate\Support\Str;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\Modal;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\Support\ListOf;
use MoonShine\Contracts\Core\DependencyInjection\FieldsContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Components\Component;
use MoonShine\UI\Fields\{ID, Text, Textarea, Hidden, Image, Date, File, Select};
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Alert;
use MoonShine\Actions\DeleteAction;
use MoonShine\Actions\MassDeleteAction;
use MoonShine\Filters\TextFilter;
use MoonShine\Filters\SelectFilter;
use MoonShine\Enums\PageType;
use Closure;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class SuratMasukResource extends ModelResource
{
    protected string $model = SuratMasuk::class;

    protected string $title = 'Surat Masuk';
    protected string $subTitle = 'Manajemen Data Surat Masuk';
    protected string $column = 'nomor_surat';
    protected array $with = [];

   
    public function setLabel(): string
    {
        return 'Surat Masuk';
    }

    public function setPluralLabel(): string
    {
        return 'Surat Masuk';
    }

    public function setIcon(): string
    {
        return 'heroicons.outline.inbox-arrow-down';
    }

    public function afterSave(mixed $item, FieldsContract $fields): mixed
    {
        if ($item->file_surat) {

            $qrCodeContentUrl = route('surat_masuk.view', ['id' => $item->id]);
            $qrCodeDirPath = 'surat_masuk/qrcode';
            $qrCodeFileName = 'qrcode_' . $item->id . '.svg';
            $qrCodeSvgData = QrCode::size(200)->generate($qrCodeContentUrl);

            Storage::disk('public')->put($qrCodeDirPath . '/' . $qrCodeFileName, $qrCodeSvgData);
            $item->update(['qr_code' => $qrCodeDirPath . '/' . $qrCodeFileName]);
            $item->refresh();
        }
        return parent::afterSave($item, $fields);
    }

    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Nomor Surat', 'nomor_surat')->sortable(),
            Text::make('Pengirim', 'pengirim')->sortable(),
            Text::make('Perihal', 'perihal'),
            Date::make('Tanggal Surat', 'tanggal_surat')->sortable(),
            Date::make('Tanggal Diterima', 'tanggal_diterima')->sortable(),
            File::make('File Surat', 'file_surat')
                ->disk('public')
                ->dir('surat_masuk')
                ->removable(false),
           
            Image::make('QR Code', 'qr_code')
                ->disk('public')
                ->dir('surat_masuk/qrcode')
                ->removable(false),
        ];
    }

    protected function formFields(): iterable
    {
        return [
            Box::make([
                Text::make('Nomor Surat', 'nomor_surat')
                    ->required()
                    ->hint('Nomor unik surat masuk. Contoh: SK/2025/001.'),

                Text::make('Pengirim', 'pengirim')
                    ->required()
                    ->hint('Nama atau instansi pengirim surat.'),

                Text::make('Perihal', 'perihal')
                    ->required()
                    ->hint('Pokok atau isi ringkas surat.'),

                Date::make('Tanggal Surat', 'tanggal_surat')
                    ->required()
                    ->format('Y-m-d')
                    ->hint('Tanggal surat dibuat oleh pengirim.'),

                Date::make('Tanggal Diterima', 'tanggal_diterima')
                    ->required()
                    ->format('Y-m-d')
                    ->hint('Tanggal surat diterima di instansi Anda.'),

                File::make('File Surat', 'file_surat')
                    ->dir('surat_masuk')
                    ->disk('public')
                    ->allowedExtensions(['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'])
                    ->removable()
                    ->nullable()
                    ->hint('Unggah file surat (PDF, DOC, Gambar).'),
                Textarea::make('Lampiran', 'lampiran')

            ])
        ];
    }

    protected function detailFields(): iterable
    {
        return [
            ID::make(),
            Text::make('Nomor Surat', 'nomor_surat'),
            Text::make('Pengirim', 'pengirim'),
            Text::make('Perihal', 'perihal'),
            Date::make('Tanggal Surat', 'tanggal_surat'),
            Date::make('Tanggal Diterima', 'tanggal_diterima'),
            File::make('File Surat', 'file_surat')
                ->disk('public')
                ->dir('surat_masuk')
                ->removable(false),
            Image::make('QR Code', 'qr_code')
                ->disk('public')
                ->dir('surat_masuk/qrcode')
                ->removable(false),
        ];
    }

    public function filters(): iterable
    {
        return [
            Text::make('Nomor Surat', 'nomor_surat'),
            Text::make('Pengirim', 'pengirim'),
        ];
    }

protected function indexButtons(): ListOf
{

return parent::indexButtons()
    ->add(
        ActionButton::make('Buat Disposisi')
            ->icon('envelope')
            ->primary()
            ->inModal(
                title: 'Buat Disposisi Surat',
                name: 'create-disposisi-global',
                builder: function () {
                    return Modal::make(
                        'Buat Disposisi Surat',
                    );
                }
            )
            ->canSee(fn() => auth('moonshine')->user()->moonshineUserRole->name === 'Kepala Sekolah')
    );
}

    public function actions(): array
    {
        return [
            DeleteAction::make('Hapus'),
            MassDeleteAction::make('Hapus Massal'),
        ];
    }

    public function rules(mixed $item): array
    {
        return [
            'nomor_surat' => ['required', 'string', 'max:255'],
            'pengirim' => ['required', 'string', 'max:255'],
            'perihal' => ['required', 'string', 'max:255'],
            'tanggal_surat' => ['required', 'date'],
        ];
    }

    public function getPageType(): PageType
    {
        return PageType::INDEX;
    }

    protected function components(): iterable
    {
        return [
            Modal::make(
                'Title',
                'Content',
            )
                ->name('my-modal'),
        ];
    }
}
