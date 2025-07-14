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
    ID, Text, Date, Email, Phone, Enum, Textarea, Number, Image, File, Select
};
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Actions\DeleteAction;
use MoonShine\Actions\MassDeleteAction;
use MoonShine\Fields\Filters\TextFilter;
use MoonShine\Fields\Filters\SelectFilter;
use MoonShine\Enums\PageType;

class PegawaiResource extends ModelResource
{
    protected string $model = Pegawai::class;
    protected string $title = 'Pegawai';
    protected string $subTitle = 'Manajemen Data Pegawai';
    protected string $column = 'nama_lengkap';
    protected array $with = ['pangkatGolongan', 'mataPelajaran'];

    public function setLabel(): string
    {
        return 'Pegawai';
    }

    public function setPluralLabel(): string
    {
        return 'Pegawai';
    }

    protected function indexFields(): iterable
    {
        return [
            Text::make('NIP', 'nip')->sortable(),
            Text::make('Nama Lengkap', 'nama_lengkap')->sortable(),
            Text::make('Jenis Kelamin', 'jenis_kelamin'),
            Text::make('Status Kepegawaian', 'status_kepegawaian'),
            Text::make('Jabatan', 'jabatan'),
            BelongsTo::make('Pangkat/Golongan', 'pangkatGolongan', formatted: 'pangkat_golongan'), // Tampilkan relasi
            BelongsTo::make('Mata Pelajaran', 'mataPelajaran', formatted: 'nama_mapel'), // Tampilkan relasi
            Text::make('Total Jam Mengajar', 'total_jam_mengajar'),
        ];
    }

    protected function formFields(): iterable
    {
        return [
            Box::make('Informasi Pribadi', [
                Text::make('NIP', 'nip')
                    ->nullable()
                    ->hint('Nomor Induk Pegawai.'),
                Text::make('Nama Lengkap', 'nama_lengkap')
                    ->required()
                    ->hint('Nama lengkap pegawai.'),
                Text::make('Gelar Depan', 'gelar_depan')
                    ->nullable()
                    ->hint('Gelar akademik di depan nama (contoh: Dr., Ir.).'),
                Text::make('Gelar Belakang', 'gelar_belakang')
                    ->nullable()
                    ->hint('Gelar akademik di belakang nama (contoh: S.Pd., M.Kom.).'),
                Text::make('Tempat Lahir', 'tempat_lahir')
                    ->nullable()
                    ->hint('Kota atau kabupaten tempat lahir.'),
                Date::make('Tanggal Lahir', 'tanggal_lahir')
                    ->nullable()
                    ->format('Y-m-d')
                    ->hint('Tanggal lahir pegawai.'),
                Enum::make('Jenis Kelamin', 'jenis_kelamin')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ])
                    ->required()
                    ->hint('Pilih jenis kelamin.'),
                Text::make('Agama', 'agama')
                    ->nullable()
                    ->hint('Agama yang dianut.'),
                Enum::make('Status Perkawinan', 'status_perkawinan')
                    ->options([
                        'Menikah' => 'Menikah',
                        'Belum Menikah' => 'Belum Menikah',
                        'Cerai' => 'Cerai',
                    ])
                    ->nullable()
                    ->hint('Pilih status perkawinan.'),
                Textarea::make('Alamat', 'alamat')
                    ->nullable()
                    ->hint('Alamat lengkap tempat tinggal.'),
                Phone::make('No HP', 'no_hp')
                    ->nullable()
                    ->hint('Nomor telepon seluler.'),
                Email::make('Email', 'email')
                    ->nullable()
                    ->hint('Alamat email aktif.'),
            ]),

            Box::make('Informasi Kepegawaian', [
                Select::make('Status Kepegawaian', 'status_kepegawaian') // Menggunakan Select untuk opsi terbatas
                    ->options([
                        'PNS' => 'PNS',
                        'PPPK' => 'PPPK',
                        'Honor Provinsi' => 'Honor Provinsi',
                        'Honor Sekolah' => 'Honor Sekolah',
                    ])
                    ->required()
                    ->hint('Status kepegawaian pegawai.'),

                Select::make('Jabatan', 'jabatan') // Menggunakan Select untuk opsi terbatas
                    ->options([
                        'Kepala Sekolah' => 'Kepala Sekolah',
                        'Wakil Kepala' => 'Wakil Kepala',
                        'Guru' => 'Guru',
                        'Tata Usaha' => 'Tata Usaha',
                        'Operator' => 'Operator',
                        'Satpam' => 'Satpam',
                    ])
                    ->required()
                    ->hint('Jabatan pegawai di instansi.'),

                BelongsTo::make('Pangkat/Golongan', 'pangkatGolongan', resource: PangkatGolonganResource::class, formatted: 'pangkat_golongan')
                    ->nullable()
                    ->searchable()
                    ->hint('Pangkat dan golongan pegawai.'),

                Date::make('TMT Pengangkatan', 'tmt_pengangkatan')
                    ->nullable()
                    ->format('Y-m-d')
                    ->hint('Tanggal Mulai Terhitung Pengangkatan.'),
            ]),

            Box::make('Informasi Pendidikan', [
                Text::make('Pendidikan Terakhir', 'pendidikan_terakhir')
                    ->nullable()
                    ->hint('Jenjang pendidikan terakhir.'),
                Text::make('Program Studi', 'program_studi')
                    ->nullable()
                    ->hint('Program studi yang diambil.'),
                Text::make('Instansi Pendidikan', 'instansi_pendidikan')
                    ->nullable()
                    ->hint('Nama perguruan tinggi atau lembaga pendidikan.'),
                Text::make('Tahun Lulus', 'tahun_lulus')
                    ->nullable()
                    ->hint('Tahun kelulusan.'),
            ]),

            Box::make('Informasi Mengajar', [
                BelongsTo::make('Mata Pelajaran', 'mataPelajaran', resource: MataPelajaranResource::class, formatted: 'nama_mapel')
                    ->nullable()
                    ->searchable()
                    ->hint('Mata pelajaran yang diampu.'),
                Number::make('Total Jam Mengajar', 'total_jam_mengajar')
                    ->nullable()
                    ->min(0)
                    ->hint('Total jam mengajar per minggu/bulan.'),
            ]),

            Box::make('Dokumen & Lain-lain', [
                Image::make('Foto', 'foto')
                    ->disk('public')
                    ->dir('pegawai/foto')
                    ->nullable()
                    ->removable()
                    ->hint('Foto profil pegawai.'),
                File::make('SK CPNS', 'sk_cpns')
                    ->disk('public')
                    ->dir('pegawai/dokumen')
                    ->nullable()
                    ->removable()
                    ->allowedExtensions(['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'])
                    ->hint('Surat Keputusan Calon Pegawai Negeri Sipil.'),
                File::make('SK PNS', 'sk_pns')
                    ->disk('public')
                    ->dir('pegawai/dokumen')
                    ->nullable()
                    ->removable()
                    ->allowedExtensions(['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'])
                    ->hint('Surat Keputusan Pegawai Negeri Sipil.'),
                Text::make('NPWP', 'npwp')
                    ->nullable()
                    ->hint('Nomor Pokok Wajib Pajak.'),
                Text::make('BPJS Kesehatan', 'bpjs_kesehatan')
                    ->nullable()
                    ->hint('Nomor BPJS Kesehatan.'),
                Text::make('BPJS Ketenagakerjaan', 'bpjs_ketenagakerjaan')
                    ->nullable()
                    ->hint('Nomor BPJS Ketenagakerjaan.'),
            ])
        ];
    }

    protected function detailFields(): iterable
    {
        return [
            Text::make('NIP', 'nip'),
            Text::make('Nama Lengkap', 'nama_lengkap'),
            Text::make('Gelar Depan', 'gelar_depan'),
            Text::make('Gelar Belakang', 'gelar_belakang'),
            Text::make('Tempat Lahir', 'tempat_lahir'),
            Date::make('Tanggal Lahir', 'tanggal_lahir')->format('d M Y'),
            Text::make('Jenis Kelamin', 'jenis_kelamin'),
            Text::make('Agama', 'agama'),
            Text::make('Status Perkawinan', 'status_perkawinan'),
            Textarea::make('Alamat', 'alamat'),
            Phone::make('No HP', 'no_hp'),
            Email::make('Email', 'email'),

            // Informasi Kepegawaian
            Text::make('Status Kepegawaian', 'status_kepegawaian'),
            Text::make('Jabatan', 'jabatan'),
            BelongsTo::make('Pangkat/Golongan', 'pangkatGolongan', formatted: 'pangkat_golongan'),
            Date::make('TMT Pengangkatan', 'tmt_pengangkatan')->format('d M Y'),

            // Informasi Pendidikan
            Text::make('Pendidikan Terakhir', 'pendidikan_terakhir'),
            Text::make('Program Studi', 'program_studi'),
            Text::make('Instansi Pendidikan', 'instansi_pendidikan'),
            Text::make('Tahun Lulus', 'tahun_lulus'),

            // Informasi Mengajar
            BelongsTo::make('Mata Pelajaran', 'mataPelajaran', formatted: 'nama_mapel'),
            Number::make('Total Jam Mengajar', 'total_jam_mengajar'),

            // Dokumen & Lain-lain
            Image::make('Foto', 'foto')->disk('public')->dir('pegawai/foto'),
            File::make('SK CPNS', 'sk_cpns')->disk('public')->dir('pegawai/dokumen'),
            File::make('SK PNS', 'sk_pns')->disk('public')->dir('pegawai/dokumen'),
            Text::make('NPWP', 'npwp'),
            Text::make('BPJS Kesehatan', 'bpjs_kesehatan'),
            Text::make('BPJS Ketenagakerjaan', 'bpjs_ketenagakerjaan'),
        ];
    }

    public function filters(): array
    {
        return [
            Text::make('NIP', 'nip'),
            Text::make('Nama Lengkap', 'nama_lengkap'),
            Select::make('Status Kepegawaian', 'status_kepegawaian')->options([
                '' => 'Semua Status',
                'PNS' => 'PNS',
                'PPPK' => 'PPPK',
                'Honor Provinsi' => 'Honor Provinsi',
                'Honor Sekolah' => 'Honor Sekolah',
            ]),
            Select::make('Jabatan', 'jabatan')->options([
                '' => 'Semua Jabatan',
                'Kepala Sekolah' => 'Kepala Sekolah',
                'Wakil Kepala' => 'Wakil Kepala',
                'Guru' => 'Guru',
                'Tata Usaha' => 'Tata Usaha',
                'Operator' => 'Operator',
                'Satpam' => 'Satpam',
            ]),
        ];
    }

    public function actions(): array
    {
        return [
            DeleteAction::make('Hapus'),
            MassDeleteAction::make('Hapus Massal'),
        ];
    }

    protected function rules(mixed $item): array
    {
        return [
            'nip' => ['nullable', 'string', 'max:255', 'unique:pegawai,nip,' . $item->id], // NIP unik
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'gelar_depan' => ['nullable', 'string', 'max:255'],
            'gelar_belakang' => ['nullable', 'string', 'max:255'],
            'tempat_lahir' => ['nullable', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenis_kelamin' => ['required', 'string', 'in:Laki-laki,Perempuan'],
            'agama' => ['nullable', 'string', 'max:255'],
            'status_perkawinan' => ['nullable', 'string', 'in:Menikah,Belum Menikah,Cerai'],
            'alamat' => ['nullable', 'string'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255', 'unique:pegawai,email,' . $item->id], // Email unik
            'status_kepegawaian' => ['required', 'string', 'in:PNS,PPPK,Honor Provinsi,Honor Sekolah'],
            'jabatan' => ['required', 'string', 'in:Kepala Sekolah,Wakil Kepala,Guru,Tata Usaha,Operator,Satpam'],
            'pangkat_golongan_id' => ['nullable', 'exists:pangkat_golongan,id'], // Validasi relasi
            'tmt_pengangkatan' => ['nullable', 'date'],
            'pendidikan_terakhir' => ['nullable', 'string', 'max:255'],
            'program_studi' => ['nullable', 'string', 'max:255'],
            'instansi_pendidikan' => ['nullable', 'string', 'max:255'],
            'tahun_lulus' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 5)],
            'mata_pelajaran_id' => ['nullable', 'exists:mata_pelajaran,id'], // Validasi relasi
            'total_jam_mengajar' => ['nullable', 'integer', 'min:0'],
            'foto' => ['nullable', 'image', 'max:2048'], // Max 2MB
            'sk_cpns' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5000'], // Max 5MB
            'sk_pns' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5000'], // Max 5MB
            'npwp' => ['nullable', 'string', 'max:255'],
            'bpjs_kesehatan' => ['nullable', 'string', 'max:255'],
            'bpjs_ketenagakerjaan' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function getPageType(): PageType
    {
        return PageType::INDEX;
    }
}
