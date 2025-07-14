<?php

namespace App\Exports;

use App\Models\SuratMasuk;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class SuratMasukExport implements FromQuery, WithHeadings, WithMapping, WithTitle
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
            'Pengirim',
            'Perihal',
            'Tanggal Surat',
            'Tanggal Diterima',
        ];
    }

    /**
     * @var SuratMasuk $suratMasuk
     */
    public function map($suratMasuk): array
    {
        return [
            $suratMasuk->nomor_surat,
            $suratMasuk->pengirim,
            $suratMasuk->perihal,
            $suratMasuk->tanggal_surat,
            $suratMasuk->tanggal_diterima,
        ];
    }

    public function title(): string
    {
        return substr('Laporan Surat Masuk', 0, 31);
    }
}
