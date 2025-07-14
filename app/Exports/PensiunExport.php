<?php

namespace App\Exports;

use App\Models\Pensiun;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class PensiunExport implements FromQuery, WithHeadings, WithMapping, WithTitle
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
            'Jenis Pensiun',
            'Tanggal Usulan',
            'Status Pengajuan',
            'Keterangan',
            'Nomor Surat',
            'Tanggal Surat',
        ];
    }

    /**
     * @var Pensiun $pensiun
     */
    public function map($pensiun): array
    {
        return [
            $pensiun->pegawai->nama_lengkap ?? '-', // Mengakses nama pegawai melalui relasi
            $pensiun->jenis_pensiun,
            $pensiun->tanggal_usulan,
            $pensiun->status_pengajuan,
            $pensiun->keterangan,
            $pensiun->nomor_surat,
            $pensiun->tanggal_surat,
        ];
    }

    public function title(): string
    {
        return substr('Laporan Pensiun Pegawai', 0, 31);
    }
}
