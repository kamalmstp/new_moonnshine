<?php

namespace App\Exports;

use App\Models\PerjalananDinas;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class PerjalananDinasExport implements FromQuery, WithHeadings, WithMapping, WithTitle
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
            'Jenis Perjalanan',
            'Tanggal Berangkat',
            'Tanggal Kembali',
            'Tujuan',
            'Keterangan',
            'Status Perjalanan',
            'Nomor Surat Tugas',
            'Tanggal Surat Tugas',
        ];
    }

    /**
     * @var PerjalananDinas $perjalananDinas
     */
    public function map($perjalananDinas): array
    {
        return [
            $perjalananDinas->pegawai->nama_lengkap ?? '-',
            $perjalananDinas->jenis_perjalanan,
            $perjalananDinas->tanggal_berangkat,
            $perjalananDinas->tanggal_kembali,
            $perjalananDinas->tujuan,
            $perjalananDinas->keterangan,
            $perjalananDinas->status_perjalanan,
            $perjalananDinas->nomor_surat_tugas,
            $perjalananDinas->tanggal_surat_tugas,
        ];
    }

    public function title(): string
    {
        return substr('Laporan Perjalanan Dinas', 0, 31);
    }
}
