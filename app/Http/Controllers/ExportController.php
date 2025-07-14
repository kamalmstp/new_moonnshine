<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel; // Tetap digunakan untuk XLSX
use App\Exports\PegawaiExport; // Tetap digunakan untuk XLSX
// use App\Exports\PegawaiPdfExport; // Tidak lagi diperlukan untuk ekspor PDF langsung

use Dompdf\Dompdf; // Import kelas Dompdf
use Dompdf\Options; // Import kelas Options Dompdf

class ExportController extends Controller
{
    /**
     * Menangani ekspor data pegawai ke format XLSX.
     * Data yang diekspor akan difilter berdasarkan parameter di request.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportPegawaiXlsx(Request $request)
    {
        // Ambil filter dari request
        $statusKepegawaian = $request->query('status_kepegawaian');
        $jabatan = $request->query('jabatan');

        // Buat query yang sama dengan yang ada di halaman laporan
        $pegawaiQuery = Pegawai::query()
            ->with(['pangkatGolongan', 'mataPelajaran']) // Eager load relasi untuk menghindari N+1 problem
            ->when($statusKepegawaian, fn ($q, $val) => $q->where('status_kepegawaian', $val))
            ->when($jabatan, fn ($q, $val) => $q->where('jabatan', $val))
            ->orderBy('nama_lengkap');

        // Nama file akan menjadi "laporan_pegawai_YYYYMMDD_HHMMSS.xlsx"
        $fileName = 'laporan_pegawai_' . now()->format('Ymd_His') . '.xlsx';

        // Menggunakan Maatwebsite\Excel untuk download
        return Excel::download(new PegawaiExport($pegawaiQuery), $fileName);
    }

    /**
     * Menangani ekspor data pegawai ke format PDF secara langsung menggunakan Dompdf.
     * Data yang diekspor akan difilter berdasarkan parameter di request.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function exportPegawaiPdf(Request $request)
    {
        // Ambil filter dari request
        $statusKepegawaian = $request->query('status_kepegawaian');
        $jabatan = $request->query('jabatan');

        // Buat query yang sama dengan yang ada di halaman laporan
        $pegawaiQuery = Pegawai::query()
            ->with(['pangkatGolongan', 'mataPelajaran']) // Eager load relasi untuk menghindari N+1 problem
            ->when($statusKepegawaian, fn ($q, $val) => $q->where('status_kepegawaian', $val))
            ->when($jabatan, fn ($q, $val) => $q->where('jabatan', $val))
            ->orderBy('nama_lengkap');

        // Dapatkan data hasil query
        $pegawai = $pegawaiQuery->get();

        // Nama file akan menjadi "laporan_pegawai_YYYYMMDD_HHMMSS.pdf"
        $fileName = 'laporan_pegawai_' . now()->format('Ymd_His') . '.pdf';

        // Render view Blade menjadi HTML
        $html = view('exports.pegawai_pdf', [
            'pegawai' => $pegawai
        ])->render();

        // Konfigurasi Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true); // Penting jika ada gambar eksternal atau CSS dari URL

        // Inisialisasi Dompdf
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        // (Opsional) Atur ukuran kertas dan orientasi
        $dompdf->setPaper('A4', 'landscape'); // Contoh: A4, orientasi landscape

        // Render PDF (menghasilkan output)
        $dompdf->render();

        // Kirim PDF sebagai respons download
        return response()->streamDownload(function () use ($dompdf) {
            echo $dompdf->output();
        }, $fileName, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }
}
