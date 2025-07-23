<?php

namespace App\Exports;

use App\Models\Pelatihan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class PelatihanExport implements FromQuery, WithHeadings, WithMapping, WithTitle
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'Nama Pegawai',
            'Nama Pelatihan',
            'Tema',
            'Penyelenggara',
            'Tempat Pelatihan',
            'Tahun',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Surat Tugas',
            'Sertifikat',
        ];
    }

    /**
     * @var Pelatihan $pelatihan
     */
    public function map($pelatihan): array
    {
        return [
            $pelatihan->pegawai->nama_lengkap ?? '-', // Mengakses nama pegawai melalui relasi
            $pelatihan->nama_pelatihan ?? '-',
            $pelatihan->tema ?? '-',
            $pelatihan->penyelenggara ?? '-',
            $pelatihan->tempat_pelatihan ?? '-',
            $pelatihan->tahun ?? '-',
            $pelatihan->tanggal_mulai ?? '-',
            $pelatihan->tanggal_selesai ?? '-',
            $pelatihan->surat_tugas ?? '-',
            $pelatihan->sertifikat ?? '-',
        ];
    }

    public function title(): string
    {
        return substr('Laporan Pelatihan Pegawai', 0, 31);
    }
}
