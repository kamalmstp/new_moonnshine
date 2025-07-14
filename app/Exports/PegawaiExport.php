<?php

namespace App\Exports;

use App\Models\Pegawai;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle; // Import WithTitle untuk nama sheet

class PegawaiExport implements FromQuery, WithHeadings, WithMapping, WithTitle
{
    protected $query;

    /**
     * Konstruktor untuk menerima query Eloquent yang sudah difilter.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * Mengembalikan query Eloquent yang akan digunakan untuk mengambil data.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return $this->query;
    }

    /**
     * Mendefinisikan judul kolom (headers) untuk file Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'NIP',
            'Nama Lengkap',
            'Jenis Kelamin',
            'Status Kepegawaian',
            'Jabatan',
            'Pangkat',
            'Golongan',
            'Mata Pelajaran',
            'Total Jam Mengajar',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Agama',
            'Status Perkawinan',
            'Alamat',
            'No. HP',
            'Email',
            'TMT Pengangkatan',
            'Pendidikan Terakhir',
            'Program Studi',
            'Instansi Pendidikan',
            'Tahun Lulus',
            'NPWP',
            'BPJS Kesehatan',
            'BPJS Ketenagakerjaan',
        ];
    }

    /**
     * Memetakan setiap baris data pegawai ke format yang akan ditampilkan di Excel.
     *
     * @param Pegawai $pegawai
     * @return array
     */
    public function map($pegawai): array
    {
        return [
            $pegawai->nip,
            $pegawai->nama_lengkap,
            $pegawai->jenis_kelamin,
            $pegawai->status_kepegawaian,
            $pegawai->jabatan,
            $pegawai->pangkatGolongan->nama_pangkat ?? '-', // Mengakses relasi, berikan '-' jika null
            $pegawai->pangkatGolongan->golongan ?? '-',   // Mengakses relasi, berikan '-' jika null
            $pegawai->mataPelajaran->nama_mapel ?? '-', // Mengakses relasi, berikan '-' jika null
            $pegawai->total_jam_mengajar,
            $pegawai->tempat_lahir,
            $pegawai->tanggal_lahir,
            $pegawai->agama,
            $pegawai->status_perkawinan,
            $pegawai->alamat,
            $pegawai->no_hp,
            $pegawai->email,
            $pegawai->tmt_pengangkatan,
            $pegawai->pendidikan_terakhir,
            $pegawai->program_studi,
            $pegawai->instansi_pendidikan,
            $pegawai->tahun_lulus,
            $pegawai->npwp,
            $pegawai->bpjs_kesehatan,
            $pegawai->bpjs_ketenagakerjaan,
        ];
    }

    /**
     * Mengatur nama sheet (lembar kerja) untuk file Excel.
     * Nama sheet tidak boleh lebih dari 31 karakter.
     *
     * @return string
     */
    public function title(): string
    {
        // Ganti string ini dengan nama sheet yang Anda inginkan (maksimal 31 karakter)
        $title = 'Laporan Pegawai SMAN 2';

        // Pastikan nama sheet tidak melebihi 31 karakter untuk menghindari error
        return substr($title, 0, 31);
    }
}
