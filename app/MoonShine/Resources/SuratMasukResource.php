<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\SuratMasuk;
use Illuminate\Support\Str;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\Core\DependencyInjection\FieldsContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\{ID, Text, Textarea, Image, Date, File};
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class SuratMasukResource extends ModelResource
{
    protected string $model = SuratMasuk::class;

    protected string $title = 'Surat Masuk';

    /**
     * @return list<FieldContract>
     */

     public function afterSave(mixed $item, FieldsContract $fields): mixed
    {
        if ($item->lampiran) { 
            $fileName = basename($item->lampiran); 

            $qrCodeContentUrl = 'http://localhost:8000/surat_masuk/'.$fileName;

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
            Text::make('Nomor Surat', 'nomor_surat'),
            Date::make('Tanggal Surat', 'tanggal_surat'),
            Text::make('Pengirim', 'pengirim'),
            Text::make('Perihal', 'perihal'),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            Text::make('No. Surat', 'nomor_surat')
                    ->required()
                    ->hint('Nomor unik surat masuk. Contoh: SK/2025/001.'),
                
            Date::make('Tanggal Terima', 'tanggal_surat')
                ->format('Y-m-d')
                ->required(),
            
            Text::make('Pengirim', 'pengirim')
                ->required()
                ->hint('Nama Pengirim Surat.'),
            
            Textarea::make('Perihal', 'perihal')
                ->required()
                ->hint('Isi ringkas surat.'),

            File::make('Lampiran', 'lampiran')
                ->disk('public')
                ->dir('surat_masuk')
                ->nullable()
                ->removable()
                ->hint('Unggah file surat (PDF, Word, dll).'),
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
            ID::make(),
            Text::make('Nomor Surat', 'nomor_surat'),
            Date::make('Tanggal Surat', 'tanggal_surat'),
            Text::make('Pengirim', 'pengirim'),
            Text::make('Perihal', 'perihal'),
            Text::make('Lampiran File', 'lampiran'),
            Image::make('QR Code', 'qr_code')
                ->disk('public')
                ->dir('surat_masuk/qrcode')
                ->removable(false),
        ];
    }

    protected function rules(mixed $item): array
    {
        return [
            'nomor_surat' => ['required', 'string'],
            'tanggal_surat' => ['required', 'date'],
            'pengirim' => ['required', 'string'],
            'perihal' => ['required', 'string'],
            'lampiran' => ['nullable', 'file'],
        ];
    }
}