<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Pensiun Pegawai SMAN 2 Banjarmasin</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        /* Menggunakan font yang lebih aman untuk Dompdf dan mendukung karakter Latin Extended */
        @font-face {
            font-family: 'ArialUnicodeMS';
            src: url('{{ public_path('fonts/arial-unicode-ms.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'Arial', sans-serif; /* Fallback ke Arial jika Arial Unicode MS tidak tersedia */
            font-size: 10pt;
            margin: 0.5in; /* Margin halaman */
            color: #333;
        }

        /* Header Laporan */
        .header {
            margin-bottom: 5px; /* Sesuaikan jarak dari bagian di bawahnya */
            border-bottom: 2px solid #000; /* Garis bawah hitam seperti di gambar */
            padding-bottom: 10px;
        }
        .header table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px; /* Jarak antara kop dan akreditasi */
        }
        .header table td {
            border: none; /* Hapus border default sel tabel di header */
            padding: 0;
            vertical-align: middle; /* Rata tengah vertikal untuk konten sel */
        }
        .header .logo-cell-left {
            width: 15%; /* Lebar kolom logo kiri */
            text-align: left;
        }
        .header .logo-cell-right {
            width: 15%; /* Lebar kolom logo kanan */
            text-align: right;
        }
        .header .logo-cell-left img,
        .header .logo-cell-right img {
            max-width: 80px; /* Ukuran logo sekolah */
            height: auto;
        }
        .header .text-cell {
            width: 70%; /* Lebar kolom teks utama */
            text-align: center; /* Teks kop surat rata tengah */
            line-height: 1.2; /* Mengurangi spasi antar baris */
        }
        .header h2 {
            margin: 0;
            font-size: 14pt; /* Sesuaikan ukuran font */
            color: #000; /* Warna hitam */
        }
        .header h3 {
            margin: 0;
            font-size: 12pt; /* Sesuaikan ukuran font */
            color: #000;
        }
        .header p {
            margin: 2px 0;
            font-size: 8pt; /* Sesuaikan ukuran font */
            color: #000;
        }
        .terakreditasi {
            font-size: 9pt;
            font-weight: bold;
            text-align: right;
            margin-top: 5px; /* Jarak dari garis bawah kop */
        }

        /* Styling untuk bagian judul laporan dan periode cetak */
        .report-info {
            text-align: center;
            margin-top: 10px; /* Jarak dari garis kop */
            margin-bottom: 20px; /* Jarak dari tabel data */
        }
        .report-info .report-title {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 5px; /* Jarak antara judul laporan dan periode cetak */
            color: #000;
        }
        .report-info .print-period {
            font-size: 9pt;
            color: #555;
            margin: 0;
        }


        /* Styling Tabel Data Pensiun */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0; /* Disesuaikan karena ada .report-info di atasnya */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sedikit bayangan pada tabel */
        }
        th, td {
            border: 1px solid #ddd; /* Garis tipis untuk sel */
            padding: 10px 12px; /* Padding yang lebih baik */
            text-align: left;
            vertical-align: top;
            word-wrap: break-word; /* Memastikan teks panjang tidak keluar tabel */
        }
        th {
            background-color: #007bff; /* Warna biru untuk header tabel */
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9pt;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa; /* Warna latar belakang baris genap */
        }
        tr:hover {
            background-color: #e2e6ea; /* Efek hover (tidak terlihat di PDF, tapi praktik baik) */
        }

        /* Footer Halaman (untuk nomor halaman) */
        .footer {
            position: fixed;
            bottom: 0.5in;
            left: 0.5in;
            right: 0.5in;
            text-align: center;
            font-size: 8pt;
            color: #777;
        }

        /* Page Breaks */
        .page-break {
            page-break-after: always;
        }

        /* Styling untuk kolom tertentu agar lebih rapi */
        td:nth-child(1) { width: 3%; text-align: center; } /* No. */
        td:nth-child(2) { width: 20%; } /* Nama Pegawai */
        td:nth-child(3) { width: 15%; } /* Jenis Pensiun */
        td:nth-child(4) { width: 12%; } /* Tanggal Usulan */
        td:nth-child(5) { width: 10%; } /* Status Pengajuan */
        td:nth-child(6) { width: 25%; } /* Keterangan */
        td:nth-child(7) { width: 10%; } /* Nomor Surat */
        td:nth-child(8) { width: 10%; } /* Tanggal Surat */
        /* Sesuaikan lebar kolom sesuai kebutuhan Anda */

        /* Styling untuk Blok Tanda Tangan */
        .signature-block {
            margin-top: 40px; /* Jarak dari tabel */
            width: 40%; /* Lebar blok tanda tangan */
            float: right; /* Posisikan di kanan */
            text-align: left; /* Teks di dalam blok rata kiri */
            line-height: 1.5;
        }
        .signature-block p {
            margin: 0;
            font-size: 10pt;
        }
        .signature-block .name {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 30px; /* Jarak untuk tanda tangan */
        }
        .signature-block .title,
        .signature-block .nip {
            font-size: 9pt;
        }

    </style>
</head>
<body>
    <div class="header">
        <table>
            <tr>
                <td class="logo-cell-left">
                    <!-- Logo kiri dengan Base64 encoding -->
                    @php
                        $logoLeftPath = public_path('logo-dinas.png'); // Asumsi logo kiri
                        $logoLeftType = pathinfo($logoLeftPath, PATHINFO_EXTENSION);
                        $logoLeftData = file_get_contents($logoLeftPath);
                        $base64LogoLeft = 'data:image/' . $logoLeftType . ';base64,' . base64_encode($logoLeftData);
                    @endphp
                    <img src="{{ $base64LogoLeft }}" alt="Logo Kiri SMAN 2 Banjarmasin">
                </td>
                <td class="text-cell">
                    <h2>PEMERINTAH PROVINSI KALIMANTAN SELATAN</h2>
                    <h3>DINAS PENDIDIKAN DAN KEBUDAYAAN</h3>
                    <h3>SMA NEGERI 2 BANJARMASIN</h3>
                    <p>Jalan Mulawarman No. 21 Tlk. Dalam, Kec. Banjarmasin Tengah, Kota Banjarmasin 70115,</p>
                    <p>Telepon (0511) 3353106,</p>
                    <p>Laman sman2banjarmasin.sch.id, Pos-el bjm.sman2.mlw@gmail.com</p>
                </td>
                <td class="logo-cell-right">
                    <!-- Logo kanan dengan Base64 encoding -->
                    @php
                        // Asumsi ada logo kedua yang berbeda, jika sama gunakan logoLeftPath
                        $logoRightPath = public_path('logo-app.png'); // Anda perlu menyediakan file ini
                        $logoRightType = pathinfo($logoRightPath, PATHINFO_EXTENSION);
                        $logoRightData = file_get_contents($logoRightPath);
                        $base64LogoRight = 'data:image/' . $logoRightType . ';base64,' . base64_encode($logoRightData);
                    @endphp
                    <img src="{{ $base64LogoRight }}" alt="Logo Kanan SMAN 2 Banjarmasin">
                </td>
            </tr>
        </table>
        <div class="terakreditasi">
            Terakreditasi A (Unggul)
        </div>
    </div>

    <!-- Bagian Judul Laporan dan Periode Cetak -->
    <div class="report-info">
        <div class="report-title">LAPORAN DATA PENSIUN PEGAWAI</div> <!-- Judul disesuaikan -->
        <p class="print-period">Periode Cetak: {{ \Carbon\Carbon::now()->format('d F Y H:i:s') }} WITA</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Pegawai</th>
                <th>Jenis Pensiun</th>
                <th>Tgl. Usulan</th>
                <th>Status Pengajuan</th>
                <th>Keterangan</th>
                <th>No. Surat</th>
                <th>Tgl. Surat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pensiun as $index => $p)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $p->pegawai->nama_lengkap ?? '-' }}</td>
                <td>{{ $p->jenis_pensiun ?? '-' }}</td>
                <td>{{ $p->tanggal_usulan ?? '-' }}</td>
                <td>{{ $p->status_pengajuan ?? '-' }}</td>
                <td>{{ $p->keterangan ?? '-' }}</td>
                <td>{{ $p->nomor_surat ?? '-' }}</td>
                <td>{{ $p->tanggal_surat ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; color: #777;">Tidak ada data pensiun yang tersedia.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Blok Tanda Tangan -->
    <div class="signature-block">
        <p>Banjarmasin, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
        <p>Plt. Kepala Sekolah,</p>
        <br><br><br> <!-- Jarak untuk tanda tangan manual -->
        <p class="name">H. MUKENIANSYAH, S.Pd., M.I.Kom.</p>
        <p class="title">Pembina Tk. I</p>
        <p class="nip">NIP 196507071997021002</p>
    </div>

    <!-- Footer untuk nomor halaman (Dompdf akan mengisinya secara otomatis) -->
    <script type="text/php">
        if (isset($pdf)) {
            $text = "Halaman {PAGE_NUM} dari {PAGE_COUNT}";
            $size = 8;
            $font = $fontMetrics->getFont("Arial", "normal");
            $width = $fontMetrics->getTextWidth($text, $font, $size);
            $x = ($pdf->get_width() - $width) / 2;
            $y = $pdf->get_height() - 30;
            $pdf->page_text($x, $y, $text, $font, $size, array(0,0,0));
        }
    </script>
</body>
</html>
