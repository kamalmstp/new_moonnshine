Schema::create('pangkat_golongan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pangkat');
            $table->string('golongan');
            $table->timestamps();
        });

Schema::create('mata_pelajaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama_mapel');
            $table->string('kode_mapel')->nullable();
            $table->timestamps();
        });

Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->nullable()->unique();
            $table->string('nama_lengkap');
            $table->string('gelar_depan')->nullable();
            $table->string('gelar_belakang')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('agama')->nullable();
            $table->enum('status_perkawinan', ['Menikah', 'Belum Menikah', 'Cerai'])->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('email')->nullable();

            // Informasi Kepegawaian
            $table->enum('status_kepegawaian', ['PNS', 'PPPK', 'Honor Provinsi', 'Honor Sekolah'])->nullable();
            $table->enum('jabatan', ['Kepala Sekolah', 'Wakil Kepala', 'Guru', 'Tata Usaha', 'Operator', 'Satpam'])->nullable();
            $table->foreignId('pangkat_golongan_id')->nullable()->constrained('pangkat_golongan')->onDelete('set null');
            $table->date('tmt_pengangkatan')->nullable();
            $table->string('pendidikan_terakhir')->nullable();
            $table->string('program_studi')->nullable();
            $table->string('instansi_pendidikan')->nullable();
            $table->string('tahun_lulus')->nullable();
            $table->foreignId('mata_pelajaran_id')->nullable()->constrained('mata_pelajaran')->onDelete('set null');
            $table->integer('total_jam_mengajar')->nullable();

            // Relasi ke user login (jika diaktifkan)
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            // Dokumen opsional
            $table->string('foto')->nullable();
            $table->string('sk_cpns')->nullable();
            $table->string('sk_pns')->nullable();
            $table->string('npwp')->nullable();
            $table->string('bpjs_kesehatan')->nullable();
            $table->string('bpjs_ketenagakerjaan')->nullable();

            $table->timestamps();
        });

Schema::create('riwayat_pendidikan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->onDelete('cascade');
            $table->string('tingkat'); // SMA, D3, S1, S2
            $table->string('program_studi')->nullable();
            $table->string('instansi');
            $table->string('tahun_lulus');
            $table->string('ijazah')->nullable(); // upload file
            $table->timestamps();
        });

Schema::create('riwayat_jabatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->onDelete('cascade');
            $table->string('jabatan');
            $table->date('tmt_jabatan')->nullable();
            $table->string('sk_jabatan')->nullable(); // file upload
            $table->timestamps();
        });

Schema::create('riwayat_pangkat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->onDelete('cascade');
            $table->foreignId('pangkat_golongan_id')->nullable()->constrained('pangkat_golongan')->onDelete('set null');
            $table->date('tmt_pangkat')->nullable();
            $table->string('sk_pangkat')->nullable();
            $table->timestamps();
        });

Schema::create('pelatihan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->onDelete('cascade');
            $table->string('nama_pelatihan');
            $table->string('penyelenggara')->nullable();
            $table->string('tahun')->nullable();
            $table->string('sertifikat')->nullable();
            $table->timestamps();
        });

Schema::create('keahlian_pegawai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->onDelete('cascade');
            $table->string('nama_keahlian');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

Schema::create('kompetensi_guru', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->onDelete('cascade');
            $table->string('jenis_kompetensi'); // Pedagogik, Profesional, Sosial, dll
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->onDelete('cascade');
            $table->string('jenis_cuti'); // tahunan, sakit, melahirkan, dll
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->text('alasan')->nullable();
            $table->string('status')->default('diproses'); // diproses, disetujui, ditolak
            $table->timestamps();
        });

        Schema::create('mutasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->onDelete('cascade');
            $table->string('jenis_mutasi'); // internal, eksternal
            $table->date('tanggal_mutasi')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('sk_mutasi')->nullable(); // file upload
            $table->timestamps();
        });

        Schema::create('pensiun', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->onDelete('cascade');
            $table->string('jenis_pensiun')->nullable(); // BUP, permintaan sendiri
            $table->date('tanggal_usulan');
            $table->string('sk_pensiun')->nullable(); // file upload
            $table->timestamps();
        });

        Schema::create('perjalanan_dinas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->onDelete('cascade');
            $table->string('tujuan');
            $table->date('tanggal_berangkat');
            $table->date('tanggal_kembali');
            $table->string('keterangan')->nullable();
            $table->string('surat_tugas')->nullable();
            $table->timestamps();
        });

        Schema::create('surat_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat');
            $table->date('tanggal_surat');
            $table->string('pengirim');
            $table->string('perihal');
            $table->string('lampiran')->nullable(); // file
            $table->string('qr_code')->nullable(); // untuk tracking
            $table->timestamps();
        });

        Schema::create('surat_keluar', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat');
            $table->date('tanggal_surat');
            $table->string('tujuan');
            $table->string('perihal');
            $table->string('lampiran')->nullable();
            $table->string('qr_code')->nullable(); // untuk tracking
            $table->timestamps();
        });

        Schema::create('arsip_dokumen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawai')->onDelete('set null');
            $table->string('nama_dokumen');
            $table->string('file_path');
            $table->timestamps();
        });

<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\ArsipDokumen;
use App\Models\Pegawai;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use MoonShine\Fields\File;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Fields\Date;
use MoonShine\Fields\Select;
use MoonShine\Fields\BelongsTo;
use MoonShine\Resources\ModelResource;

class ArsipDokumenResource extends ModelResource
{
    protected string $model = ArsipDokumen::class;

    protected string $title = 'Arsip Dokumen';

    public function formFields(): array
    {
        return [
            Text::make('Nama Dokumen')->required(),

            Select::make('Jenis Dokumen')->options([
                'Surat Masuk' => 'Surat Masuk',
                'Surat Keluar' => 'Surat Keluar',
                'Laporan' => 'Laporan',
                'Memo' => 'Memo',
                'Keputusan' => 'Keputusan',
                'Lainnya' => 'Lainnya',
            ])->required(),

            Text::make('Nomor Surat')->required(),

            Date::make('Tanggal Surat')->required(),

            Text::make('Perihal')->required(),

            BelongsTo::make('Pegawai', 'pegawai', resource: PegawaiResource::class)
                ->nullable(),

            File::make('File Dokumen', 'file_path')
                ->disk('public')
                ->dir('arsip')
                ->name(fn($file) => Str::random(40) . '.' . $file->getClientOriginalExtension())
                ->allowedExtensions(['pdf', 'docx', 'jpg', 'png']),

            File::make('QR Code', 'qr_code')
                ->disk('public')
                ->dir('qrcodes')
                ->readonly()
                ->hideOnForm(), // Tidak diinput manual
        ];
    }

    public function indexFields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Nama Dokumen'),
            Text::make('Jenis Dokumen'),
            Text::make('Nomor Surat'),
            Date::make('Tanggal Surat'),
            Text::make('Perihal'),
        ];
    }

    public function detailFields(): array
    {
        return [
            ID::make(),
            Text::make('Nama Dokumen'),
            Text::make('Jenis Dokumen'),
            Text::make('Nomor Surat'),
            Date::make('Tanggal Surat'),
            Text::make('Perihal'),
            Text::make('Pegawai', fn($item) => $item->pegawai?->nama_lengkap ?? '-'),
            File::make('Dokumen', 'file_path')->disk('public'),
            File::make('QR Code', 'qr_code')->disk('public'),
        ];
    }

    protected function afterCreated(ArsipDokumen $item): void
    {
        $this->generateQrCode($item);
    }

    protected function afterUpdated(ArsipDokumen $item): void
    {
        $this->generateQrCode($item);
    }

    protected function generateQrCode(ArsipDokumen $item): void
    {
        $qrPath = 'qrcodes/' . Str::random(40) . '.svg';
        $url = route('arsip.show', $item->id); // Tetap pakai ID

        \QrCode::format('svg')->size(300)->generate($url, public_path('storage/' . $qrPath));

        $item->updateQuietly(['qr_code' => $qrPath]);
    }
}