<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
// use Maatwebsite\Excel\Concerns\WithHeadings; // Dihapus karena tidak diperlukan untuk FromView PDF

class PegawaiPdfExport implements FromView // Dihapus implementasi WithHeadings
{
    protected $pegawai;

    public function __construct($pegawai)
    {
        $this->pegawai = $pegawai;
    }

    /**
     * Mengembalikan view Blade yang akan dirender menjadi PDF.
     *
     * @return View
     */
    public function view(): View
    {
        return view('exports.pegawai_pdf', [
            'pegawai' => $this->pegawai
        ]);
    }

    /*
     * Metode headings() dihapus karena WithHeadings tidak lagi diimplementasikan.
     * Struktur header laporan PDF sepenuhnya dikontrol oleh file Blade (exports.pegawai_pdf).
     */
}
