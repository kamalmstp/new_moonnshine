<?php

namespace App\Exports;

use App\Models\SuratKeluar;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class SuratKeluarExport implements FromQuery, WithHeadings, WithMapping, WithTitle
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
            'Nomor Surat',
            'Tujuan',
            'Perihal',
            'Tanggal Surat',
        ];
    }

    /**
     * @var SuratKeluar $suratKeluar
     */
    public function map($suratKeluar): array
    {
        return [
            $suratKeluar->nomor_surat,
            $suratKeluar->tujuan,
            $suratKeluar->perihal,
            $suratKeluar->tanggal_surat,
        ];
    }

    public function title(): string
    {
        return substr('Laporan Surat Keluar', 0, 31);
    }
}
