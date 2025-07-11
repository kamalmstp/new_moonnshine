<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\ArsipDokumen;
use App\Models\Pegawai;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\{ID, Text, File, Select, Date, Image};
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

/**
 * @extends ModelResource<ArsipDokumen>
 */
class ArsipDokumenResource extends ModelResource
{
    protected string $model = ArsipDokumen::class;

    protected string $title = 'Arsip Dokumen';

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make(),
            Text::make('Nama Dokumen', 'nama_dokumen'),
            Text::make('Jenis Dokumen', 'jenis_dokumen'),
            Text::make('Nomor Surat', 'nomor_surat'),
            Date::make('Tanggal Surat', 'tanggal_surat'),
            Text::make('Perihal', 'perihal'),
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
                    ->nullable(),
                Text::make('Nama Dokumen', 'nama_dokumen')->required(),
                Select::make('Jenis Dokumen', 'jenis_dokumen')->options([
                    'Surat Masuk' => 'Surat Masuk',
                    'Surat Keluar' => 'Surat Keluar',
                    'Cuti' => 'Cuti',
                    'Mutasi' => 'Mutasi',
                    'Pensiun' => 'Pensiun',
                    'Perjalanan Dinas' => 'Perjalanan Dinas',
                    'Lainnya' => 'Lainnya',
                ])->required(),
                Text::make('Nomor Surat', 'nomor_surat')->nullable(),
                Date::make('Tanggal Surat', 'tanggal_surat')->nullable(),
                Text::make('Perihal', 'perihal')->nullable(),
                File::make('File Dokumen', 'file_path')
                    ->disk('public')
                    ->dir('arsip')
                    ->storeAs(fn($file) => Str::random(40) . '.' . $file->getClientOriginalExtension())
                    ->allowedExtensions(['pdf', 'docx', 'jpg', 'png']),
            ])
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
            ID::make(),
            Text::make('Nama Dokumen', 'nama_dokumen'),
            Text::make('Jenis Dokumen', 'jenis_dokumen'),
            Text::make('Nomor Surat', 'nomor_surat'),
            Date::make('Tanggal Surat', 'tanggal_surat'),
            Text::make('Perihal', 'perihal'),
            Text::make('File', 'file_path')->link(fn($item) => asset('storage/' . $item->file_path)),
            Image::make('QR Code', 'qr_code'),
        ];
    }

    protected function afterCreated(mixed $item): mixed
    {
        $this->generateQrCode($item);
        return $item;
    }

    protected function afterUpdated(mixed $item): mixed
    {
        $this->generateQrCode($item);
        return $item;
    }

    protected function generateQrCode(ArsipDokumen $item): void
    {
        $qrPath = 'qrcodes/' . Str::random(40) . '.svg';
        $url = route('arsip.show', $item->id); // Tetap pakai ID

        \QrCode::format('svg')->size(300)->generate($url, public_path('storage/' . $qrPath));

        $item->updateQuietly(['qr_code' => $qrPath]);
    }

    /**
     * @param ArsipDokumen $item
     *
     * @return array<string, string[]|string>
     */
    protected function rules(mixed $item): array
    {
        return [
            'nama_dokumen' => ['required', 'string'],
            'jenis_dokumen' => ['required', 'string'],
            'file_path' => ['nullable', 'file'],
        ];
    }
}