<?php

namespace App\Exports;

use App\Models\Mutasi;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class MutasiExport implements FromQuery, WithHeadings, WithMapping, WithTitle
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
            'Jenis Mutasi',
            'Tanggal Mutasi',
            'Keterangan',
            'SK Mutasi',
            'Nomor Surat',
            'Tanggal Surat',
        ];
    }

    /**
     * @var Mutasi $mutasi
     */
    public function map($mutasi): array
    {
        return [
            $mutasi->pegawai->nama_lengkap ?? '-', // Mengakses nama pegawai melalui relasi
            $mutasi->jenis_mutasi,
            $mutasi->tanggal_mutasi,
            $mutasi->keterangan,
            $mutasi->sk_mutasi,
            $mutasi->nomor_surat,
            $mutasi->tanggal_surat,
        ];
    }

    public function title(): string
    {
        return substr('Laporan Mutasi Pegawai', 0, 31);
    }
}
