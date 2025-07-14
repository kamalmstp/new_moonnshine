<?php

namespace App\Exports;

use App\Models\Cuti;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class CutiExport implements FromQuery, WithHeadings, WithMapping, WithTitle
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
            'Jenis Cuti',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Alasan',
            'Status',
            'Nomor Surat',
            'Tanggal Surat',
        ];
    }

    /**
     * @var Cuti $cuti
     */
    public function map($cuti): array
    {
        return [
            $cuti->pegawai->nama_lengkap ?? '-', // Mengakses nama pegawai melalui relasi
            $cuti->jenis_cuti,
            $cuti->tanggal_mulai,
            $cuti->tanggal_selesai,
            $cuti->alasan,
            $cuti->status,
            $cuti->nomor_surat,
            $cuti->tanggal_surat,
        ];
    }

    public function title(): string
    {
        return substr('Laporan Cuti Pegawai', 0, 31);
    }
}
