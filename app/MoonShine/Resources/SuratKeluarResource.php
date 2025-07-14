<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\SuratKeluar;
use Illuminate\Support\Str;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\Core\DependencyInjection\FieldsContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\{ID, Text, Textarea, Image, Date, File, Select};
use MoonShine\UI\Components\Layout\Box;
use MoonShine\Actions\DeleteAction;
use MoonShine\Actions\MassDeleteAction;
use MoonShine\Enums\PageType;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class SuratKeluarResource extends ModelResource
{
    protected string $model = SuratKeluar::class;

    protected string $title = 'Surat Keluar';
    protected string $subTitle = 'Manajemen Data Surat Keluar';
    protected string $column = 'nomor_surat';
    protected array $with = [];

    public function setLabel(): string
    {
        return 'Surat Keluar';
    }

    public function setPluralLabel(): string
    {
        return 'Surat Keluar';
    }

    public function setIcon(): string
    {
        return 'heroicons.outline.paper-airplane';
    }

    public function afterSave(mixed $item, FieldsContract $fields): mixed
    {
        if ($item->file_surat) {
            $qrCodeContentUrl = route('surat_keluar.view', ['id' => $item->id]);
            
            $qrCodeDirPath = 'surat_keluar/qrcode';
            
            $qrCodeFileName = 'qrcode_' . $item->id . '.svg';
            $qrCodeSvgData = QrCode::size(200)->generate($qrCodeContentUrl);

            Storage::disk('public')->put($qrCodeDirPath . '/' . $qrCodeFileName, $qrCodeSvgData);
            $item->update(['qr_code' => $qrCodeDirPath . '/' . $qrCodeFileName]);
            $item->refresh();
        }
        return parent::afterSave($item, $fields);
    }

    public function redirectAfterSave(mixed $item): string
    {
        return $this->route('index');
    }

    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Nomor Surat', 'nomor_surat')->sortable(),
            Text::make('Tujuan', 'tujuan')->sortable(),
            Text::make('Perihal', 'perihal'),
            Date::make('Tanggal Surat', 'tanggal_surat')->sortable(),
            File::make('File Surat', 'file_surat')
                ->disk('public')
                ->dir('surat_keluar')
                ->removable(false),
            Image::make('QR Code', 'qr_code')
                ->disk('public')
                ->dir('surat_keluar/qrcode')
                ->removable(false),
        ];
    }

    protected function formFields(): iterable
    {
        return [
            Box::make([
                Text::make('Nomor Surat', 'nomor_surat')
                    ->required()
                    ->hint('Nomor unik surat keluar. Contoh: SK/KL/2025/001.'),

                Text::make('Tujuan', 'tujuan')
                    ->required()
                    ->hint('Pihak atau instansi tujuan surat.'),

                Text::make('Perihal', 'perihal')
                    ->required()
                    ->hint('Pokok atau isi ringkas surat.'),

                Date::make('Tanggal Surat', 'tanggal_surat')
                    ->required()
                    ->format('Y-m-d')
                    ->hint('Tanggal surat dibuat.'),
                
                File::make('File Surat', 'file_surat')
                    ->dir('surat_keluar')
                    ->disk('public')
                    ->allowedExtensions(['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'])
                    ->removable()
                    ->nullable()
                    ->hint('Unggah file surat (PDF, DOC, Gambar).'),
            ])
        ];
    }

    protected function detailFields(): iterable
    {
        return [
            ID::make(),
            Text::make('Nomor Surat', 'nomor_surat'),
            Text::make('Tujuan', 'tujuan'),
            Text::make('Perihal', 'perihal'),
            Date::make('Tanggal Surat', 'tanggal_surat'),
            File::make('File Surat', 'file_surat')
                ->disk('public')
                ->dir('surat_keluar')
                ->removable(false),
            Image::make('QR Code', 'qr_code')
                ->disk('public')
                ->dir('surat_keluar/qrcode')
                ->removable(false),
        ];
    }

    public function filters(): iterable
    {
        return [
            Text::make('Nomor Surat', 'nomor_surat'),
            Text::make('Tujuan', 'tujuan'),
        ];
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
            'tujuan' => ['required', 'string', 'max:255'],
            'perihal' => ['required', 'string', 'max:255'],
            'tanggal_surat' => ['required', 'date'],
        ];
    }

    public function getPageType(): PageType
    {
        return PageType::INDEX;
    }
}