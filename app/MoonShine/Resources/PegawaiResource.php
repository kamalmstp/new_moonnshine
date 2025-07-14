<?php
declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pegawai;
use App\Models\PangkatGolongan;
use App\Models\MataPelajaran;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\{
    ID, Text, Date, Email, Phone, Enum, Textarea, Number, Image, File
};
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;

/**
 * @extends ModelResource<Pegawai>
 */
class PegawaiResource extends ModelResource
{
    protected string $model = Pegawai::class;
    protected string $title = 'Pegawai';

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make(),
            Text::make('NIP', 'nip')->sortable(),
            Text::make('Nama Lengkap', 'nama_lengkap')->sortable(),
            Text::make('Status Kepegawaian', 'status_kepegawaian'),
            Text::make('Jabatan', 'jabatan'),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            Box::make([
                Text::make('NIP', 'nip')->nullable(),
                Text::make('Nama Lengkap', 'nama_lengkap')->required(),
                Text::make('Gelar Depan', 'gelar_depan')->nullable(),
                Text::make('Gelar Belakang', 'gelar_belakang')->nullable(),
                Text::make('Tempat Lahir', 'tempat_lahir')->nullable(),
                Date::make('Tanggal Lahir', 'tanggal_lahir')->nullable(),
                Enum::make('Jenis Kelamin', 'jenis_kelamin')->options([
                    'Laki-laki' => 'Laki-laki',
                    'Perempuan' => 'Perempuan',
                ]),
                Text::make('Agama', 'agama')->nullable(),
                Enum::make('Status Perkawinan', 'status_perkawinan')->options([
                    'Menikah' => 'Menikah',
                    'Belum Menikah' => 'Belum Menikah',
                    'Cerai' => 'Cerai',
                ])->nullable(),
                Textarea::make('Alamat', 'alamat')->nullable(),
                Phone::make('No HP', 'no_hp')->nullable(),
                Email::make('Email', 'email')->nullable(),

                Enum::make('Status Kepegawaian', 'status_kepegawaian')->options([
                    'PNS' => 'PNS',
                    'PPPK' => 'PPPK',
                    'Honor Provinsi' => 'Honor Provinsi',
                    'Honor Sekolah' => 'Honor Sekolah',
                ])->nullable(),

                Enum::make('Jabatan', 'jabatan')->options([
                    'Kepala Sekolah' => 'Kepala Sekolah',
                    'Wakil Kepala' => 'Wakil Kepala',
                    'Guru' => 'Guru',
                    'Tata Usaha' => 'Tata Usaha',
                    'Operator' => 'Operator',
                    'Satpam' => 'Satpam',
                ])->nullable(),

                BelongsTo::make('Pangkat/Golongan', 'pangkatGolongan', resource: PangkatGolonganResource::class, formatted: 'pangkat_golongan')
                    ->nullable()
                    ->searchable(),

                Date::make('TMT Pengangkatan', 'tmt_pengangkatan')->nullable(),
                Text::make('Pendidikan Terakhir', 'pendidikan_terakhir')->nullable(),
                Text::make('Program Studi', 'program_studi')->nullable(),
                Text::make('Instansi Pendidikan', 'instansi_pendidikan')->nullable(),
                Text::make('Tahun Lulus', 'tahun_lulus')->nullable(),

                BelongsTo::make('Mata Pelajaran', 'mataPelajaran', resource: MataPelajaranResource::class, formatted: 'nama_mapel')
                    ->nullable()
                    ->searchable(),

                Number::make('Total Jam Mengajar', 'total_jam_mengajar')->nullable(),

                Image::make('Foto', 'foto')->disk('public')->dir('pegawai/foto')->nullable(),
                File::make('SK CPNS', 'sk_cpns')->disk('public')->dir('pegawai/dokumen')->nullable(),
                File::make('SK PNS', 'sk_pns')->disk('public')->dir('pegawai/dokumen')->nullable(),
                Text::make('NPWP', 'npwp')->nullable(),
                Text::make('BPJS Kesehatan', 'bpjs_kesehatan')->nullable(),
                Text::make('BPJS Ketenagakerjaan', 'bpjs_ketenagakerjaan')->nullable(),
            ])
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return $this->formFields();
    }

    /**
     * @param Pegawai $item
     * @return array<string, string[]|string>
     */
    protected function rules(mixed $item): array
    {
        return [
            'nama_lengkap' => ['required', 'string'],
        ];
    }
}