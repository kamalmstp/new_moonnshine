<?php

namespace App\Http\Controllers;

use App\Models\{Pegawai, Cuti, Mutasi,Pensiun,PerjalananDinas,SuratMasuk, SuratKeluar, Pelatihan};
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\{PegawaiExport, CutiExport, MutasiExport, PensiunExport, PerjalananDinasExport, SuratMasukExport, SuratKeluarExport};
use Dompdf\Dompdf;
use Dompdf\Options;
use Carbon\Carbon;

class ExportController extends Controller
{

    public function exportPegawaiXlsx(Request $request)
    {
        $statusKepegawaian = $request->query('status_kepegawaian');
        $jabatan = $request->query('jabatan');

        $pegawaiQuery = Pegawai::query()
            ->with(['pangkatGolongan', 'mataPelajaran'])
            ->when($statusKepegawaian, fn ($q, $val) => $q->where('status_kepegawaian', $val))
            ->when($jabatan, fn ($q, $val) => $q->where('jabatan', $val))
            ->orderBy('nama_lengkap');

        $fileName = 'laporan_pegawai_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new PegawaiExport($pegawaiQuery), $fileName);
    }

    public function exportPegawaiPdf(Request $request)
    {
        
        $statusKepegawaian = $request->query('status_kepegawaian');
        $jabatan = $request->query('jabatan');

        
        $pegawaiQuery = Pegawai::query()
            ->with(['pangkatGolongan', 'mataPelajaran']) 
            ->when($statusKepegawaian, fn ($q, $val) => $q->where('status_kepegawaian', $val))
            ->when($jabatan, fn ($q, $val) => $q->where('jabatan', $val))
            ->orderBy('nama_lengkap');

        
        $pegawai = $pegawaiQuery->get();

        
        $fileName = 'laporan_pegawai_' . now()->format('Ymd_His') . '.pdf';

        
        $html = view('exports.pegawai_pdf', [
            'pegawai' => $pegawai
        ])->render();

        
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true); 

        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        
        $dompdf->setPaper('A4', 'landscape'); 

        
        $dompdf->render();

        
        return response()->streamDownload(function () use ($dompdf) {
            echo $dompdf->output();
        }, $fileName, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    public function exportCutiXlsx(Request $request)
    {
        
        $status = $request->query('status');
        $jenisCuti = $request->query('jenis_cuti');

        
        $cutiQuery = Cuti::query()
            ->with('pegawai') 
            ->when($status, fn ($q, $val) => $q->where('status', $val))
            ->when($jenisCuti, fn ($q, $val) => $q->where('jenis_cuti', $val))
            ->latest();

        
        $fileName = 'laporan_cuti_' . now()->format('Ymd_His') . '.xlsx';

        
        return Excel::download(new CutiExport($cutiQuery), $fileName);
    }

    public function exportCutiPdf(Request $request)
    {
        
        $status = $request->query('status');
        $jenisCuti = $request->query('jenis_cuti');

        
        $cutiQuery = Cuti::query()
            ->with('pegawai') 
            ->when($status, fn ($q, $val) => $q->where('status', $val))
            ->when($jenisCuti, fn ($q, $val) => $q->where('jenis_cuti', $val))
            ->latest();

        
        $cuti = $cutiQuery->get();

        
        $fileName = 'laporan_cuti_' . now()->format('Ymd_His') . '.pdf';

        
        $html = view('exports.cuti_pdf', [
            'cuti' => $cuti
        ])->render();

        
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        
        $dompdf->setPaper('A4', 'potrait'); 

        
        $dompdf->render();

        
        return response()->streamDownload(function () use ($dompdf) {
            echo $dompdf->output();
        }, $fileName, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    public function exportMutasiXlsx(Request $request)
    {
        // Ambil filter dari request
        $jenisMutasi = $request->query('jenis_mutasi');

        // Buat query yang sama dengan yang ada di halaman laporan mutasi
        $mutasiQuery = Mutasi::query()
            ->with('pegawai') // Eager load relasi untuk mendapatkan nama pegawai
            ->when($jenisMutasi, fn ($q, $val) => $q->where('jenis_mutasi', $val))
            ->latest();

        // Nama file akan menjadi "laporan_mutasi_YYYYMMDD_HHMMSS.xlsx"
        $fileName = 'laporan_mutasi_' . now()->format('Ymd_His') . '.xlsx';

        // Menggunakan Maatwebsite\Excel untuk download
        return Excel::download(new MutasiExport($mutasiQuery), $fileName);
    }

    public function exportMutasiPdf(Request $request)
    {
        // Ambil filter dari request
        $jenisMutasi = $request->query('jenis_mutasi');

        // Buat query yang sama dengan yang ada di halaman laporan mutasi
        $mutasiQuery = Mutasi::query()
            ->with('pegawai') // Eager load relasi untuk mendapatkan nama pegawai
            ->when($jenisMutasi, fn ($q, $val) => $q->where('jenis_mutasi', $val))
            ->latest();

        // Dapatkan data hasil query
        $mutasi = $mutasiQuery->get();

        // Nama file akan menjadi "laporan_mutasi_YYYYMMDD_HHMMSS.pdf"
        $fileName = 'laporan_mutasi_' . now()->format('Ymd_His') . '.pdf';

        // Render view Blade menjadi HTML
        $html = view('exports.mutasi_pdf', [
            'mutasi' => $mutasi
        ])->render();

        // Konfigurasi Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        // Inisialisasi Dompdf
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        // (Opsional) Atur ukuran kertas dan orientasi
        $dompdf->setPaper('A4', 'potrait'); // Contoh: A4, orientasi landscape

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

    public function exportPensiunXlsx(Request $request)
    {
        // Ambil filter dari request
        $jenisPensiun = $request->query('jenis_pensiun');
        $statusPengajuan = $request->query('status_pengajuan');

        // Buat query yang sama dengan yang ada di halaman laporan pensiun
        $pensiunQuery = Pensiun::query()
            ->with('pegawai') // Eager load relasi untuk mendapatkan nama pegawai
            ->when($jenisPensiun, fn ($q, $val) => $q->where('jenis_pensiun', $val))
            ->when($statusPengajuan, fn ($q, $val) => $q->where('status_pengajuan', $val))
            ->latest();

        // Nama file akan menjadi "laporan_pensiun_YYYYMMDD_HHMMSS.xlsx"
        $fileName = 'laporan_pensiun_' . now()->format('Ymd_His') . '.xlsx';

        // Menggunakan Maatwebsite\Excel untuk download
        return Excel::download(new PensiunExport($pensiunQuery), $fileName);
    }

    public function exportPensiunPdf(Request $request)
    {
        // Ambil filter dari request
        $jenisPensiun = $request->query('jenis_pensiun');
        $statusPengajuan = $request->query('status_pengajuan');

        // Buat query yang sama dengan yang ada di halaman laporan pensiun
        $pensiunQuery = Pensiun::query()
            ->with('pegawai') // Eager load relasi untuk mendapatkan nama pegawai
            ->when($jenisPensiun, fn ($q, $val) => $q->where('jenis_pensiun', $val))
            ->when($statusPengajuan, fn ($q, $val) => $q->where('status_pengajuan', $val))
            ->latest();

        // Dapatkan data hasil query
        $pensiun = $pensiunQuery->get();

        // Nama file akan menjadi "laporan_pensiun_YYYYMMDD_HHMMSS.pdf"
        $fileName = 'laporan_pensiun_' . now()->format('Ymd_His') . '.pdf';

        // Render view Blade menjadi HTML
        $html = view('exports.pensiun_pdf', [
            'pensiun' => $pensiun
        ])->render();

        // Konfigurasi Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

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

    public function exportPerjalananDinasXlsx(Request $request)
    {
        // Ambil filter dari request
        $jenisPerjalanan = $request->query('jenis_perjalanan');
        $statusPerjalanan = $request->query('status_perjalanan');

        // Buat query yang sama dengan yang ada di halaman laporan perjalanan dinas
        $perjalananDinasQuery = PerjalananDinas::query()
            ->with('pegawai') // Eager load relasi untuk mendapatkan nama pegawai
            ->when($jenisPerjalanan, fn ($q, $val) => $q->where('jenis_perjalanan', $val))
            ->when($statusPerjalanan, fn ($q, $val) => $q->where('status_perjalanan', $val))
            ->latest();

        // Nama file akan menjadi "laporan_perjalanan_dinas_YYYYMMDD_HHMMSS.xlsx"
        $fileName = 'laporan_perjalanan_dinas_' . now()->format('Ymd_His') . '.xlsx';

        // Menggunakan Maatwebsite\Excel untuk download
        return Excel::download(new PerjalananDinasExport($perjalananDinasQuery), $fileName);
    }

    public function exportPerjalananDinasPdf(Request $request)
    {
        // Ambil filter dari request
        $jenisPerjalanan = $request->query('jenis_perjalanan');
        $statusPerjalanan = $request->query('status_perjalanan');

        // Buat query yang sama dengan yang ada di halaman laporan perjalanan dinas
        $perjalananDinasQuery = PerjalananDinas::query()
            ->with('pegawai') // Eager load relasi untuk mendapatkan nama pegawai
            ->when($jenisPerjalanan, fn ($q, $val) => $q->where('jenis_perjalanan', $val))
            ->when($statusPerjalanan, fn ($q, $val) => $q->where('status_perjalanan', $val))
            ->latest();

        // Dapatkan data hasil query
        $perjalananDinas = $perjalananDinasQuery->get();

        // Nama file akan menjadi "laporan_perjalanan_dinas_YYYYMMDD_HHMMSS.pdf"
        $fileName = 'laporan_perjalanan_dinas_' . now()->format('Ymd_His') . '.pdf';

        // Render view Blade menjadi HTML
        $html = view('exports.perjalanan_dinas_pdf', [
            'perjalananDinas' => $perjalananDinas
        ])->render();

        // Konfigurasi Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

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

    public function exportSuratMasukXlsx(Request $request)
    {
        $pengirim = $request->query('pengirim');

        $suratMasukQuery = SuratMasuk::query()
            ->when($pengirim, fn ($q, $val) => $q->where('pengirim', 'like', '%' . $val . '%'))
            ->latest();

        $fileName = 'laporan_surat_masuk_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new SuratMasukExport($suratMasukQuery), $fileName);
    }

    public function exportSuratMasukPdf(Request $request)
    {
        $pengirim = $request->query('pengirim');

        $suratMasukQuery = SuratMasuk::query()
            ->when($pengirim, fn ($q, $val) => $q->where('pengirim', 'like', '%' . $val . '%'))
            ->latest();

        $suratMasuk = $suratMasukQuery->get();

        $fileName = 'laporan_surat_masuk_' . now()->format('Ymd_His') . '.pdf';

        $html = view('exports.surat_masuk_pdf', [
            'suratMasuk' => $suratMasuk
        ])->render();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

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

    public function exportSuratKeluarXlsx(Request $request)
    {
        $tujuan = $request->query('tujuan');

        $suratKeluarQuery = SuratKeluar::query()
            ->when($tujuan, fn ($q, $val) => $q->where('tujuan', 'like', '%' . $val . '%'))
            ->latest();

        $fileName = 'laporan_surat_keluar_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new SuratKeluarExport($suratKeluarQuery), $fileName);
    }

    public function exportSuratKeluarPdf(Request $request)
    {
        
        $tujuan = $request->query('tujuan');

        
        $suratKeluarQuery = SuratKeluar::query()
            ->when($tujuan, fn ($q, $val) => $q->where('tujuan', 'like', '%' . $val . '%'))
            ->latest();

        
        $suratKeluar = $suratKeluarQuery->get();

        
        $fileName = 'laporan_surat_keluar_' . now()->format('Ymd_His') . '.pdf';

        
        $html = view('exports.surat_keluar_pdf', [
            'suratKeluar' => $suratKeluar
        ])->render();

        
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        
        $dompdf->setPaper('A4', 'landscape'); 

        
        $dompdf->render();

        
        return response()->streamDownload(function () use ($dompdf) {
            echo $dompdf->output();
        }, $fileName, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    public function exportPelatihanXlsx(Request $request)
    {
        $tahun = $request->query('tahun');

        $pelatihanQuery = Pelatihan::query()
            ->with('pegawai')
            ->when($tahun, fn ($q, $val) => $q->where('tahun', $val)) // Filter berdasarkan 'tahun'
            ->latest();

        $fileName = 'laporan_pelatihan_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new PelatihanExport($pelatihanQuery), $fileName);
    }

    public function exportPelatihanPdf(Request $request)
    {
        $tahun = $request->query('tahun');

        $pelatihanQuery = Pelatihan::query()
            ->with('pegawai')
            ->when($tahun, fn ($q, $val) => $q->where('tahun', $val)) // Filter berdasarkan 'tahun'
            ->latest();

        $pelatihan = $pelatihanQuery->get();
        $fileName = 'laporan_pelatihan_' . now()->format('Ymd_His') . '.pdf';
        
        $html = view('exports.pelatihan_pdf', [
            'pelatihan' => $pelatihan
        ])->render();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('chroot', public_path());

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'landscape');

        $dompdf->render();

        return response()->streamDownload(function () use ($dompdf) {
            echo $dompdf->output();
        }, $fileName, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }
}
