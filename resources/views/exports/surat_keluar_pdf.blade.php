<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Surat Keluar SMAN 2 Banjarmasin</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        
        @font-face {
            font-family: 'ArialUnicodeMS';
            src: url('{{ public_path('fonts/arial-unicode-ms.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'Arial', sans-serif; 
            font-size: 10pt;
            margin: 0.5in; 
            color: #333;
        }

        
        .header {
            margin-top: -30px;
            margin-bottom: 5px; 
            border-bottom: 2px solid #000; 
            padding-bottom: 10px;
        }
        .header table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px; 
        }
        .header table td {
            border: none; 
            padding: 0;
            vertical-align: middle; 
        }
        .header .logo-cell-left {
            width: 15%; 
            text-align: left;
        }
        .header .logo-cell-right {
            width: 15%; 
            text-align: right;
        }
        .header .logo-cell-left img,
        .header .logo-cell-right img {
            max-width: 80px; 
            height: auto;
        }
        .header .text-cell {
            width: 70%; 
            text-align: center; 
            line-height: 1.2; 
        }
        .header h2 {
            margin: 0;
            font-size: 14pt; 
            color: #000; 
        }
        .header h3 {
            margin: 0;
            font-size: 12pt; 
            color: #000;
        }
        .header p {
            margin: 2px 0;
            font-size: 8pt; 
            color: #000;
        }
        .terakreditasi {
            font-size: 9pt;
            font-weight: bold;
            text-align: right;
            margin-top: 5px; 
        }

        
        .report-info {
            text-align: center;
            margin-top: 10px; 
            margin-bottom: 20px; 
        }
        .report-info .report-title {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 5px; 
            color: #000;
        }
        .report-info .print-period {
            font-size: 9pt;
            color: #555;
            margin: 0;
        }


        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0; 
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
        }
        th, td {
            border: 1px solid #ddd; 
            padding: 5px 6px; 
            text-align: left;
            vertical-align: top;
            word-wrap: break-word; 
        }
        th {
            background-color: #007bff; 
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9pt;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa; 
        }
        tr:hover {
            background-color: #e2e6ea; 
        }

        
        .footer {
            position: fixed;
            bottom: 0.5in;
            left: 0.5in;
            right: 0.5in;
            text-align: center;
            font-size: 8pt;
            color: #777;
        }

        
        .page-break {
            page-break-after: always;
        }

        
        td:nth-child(1) { width: 3%; text-align: center; } 
        td:nth-child(2) { width: 15%; } 
        td:nth-child(3) { width: 20%; } 
        td:nth-child(4) { width: 30%; } 
        td:nth-child(5) { width: 10%; } 
        td:nth-child(6) { width: 12%; } 
        
        

        
        .signature-block {
            margin-top: 20px; 
            width: 40%; 
            float: right; 
            text-align: left; 
            line-height: 1.5;
        }
        .signature-block p {
            margin: 0;
            font-size: 10pt;
        }
        .signature-block .name {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 30px; 
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
                    @php
                        $logoLeftPath = public_path('logo-dinas.png');
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
                    @php
                        $logoRightPath = public_path('logo-app.png');
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

    <div class="report-info">
        <div class="report-title">LAPORAN SURAT KELUAR</div>
        <p class="print-period">Periode Cetak: {{ \Carbon\Carbon::now()->format('d F Y H:i:s') }} WITA</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nomor Surat</th>
                <th>Tujuan</th>
                <th>Perihal</th>
                <th>Tgl. Surat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($suratKeluar as $index => $sk)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $sk->nomor_surat ?? '-' }}</td>
                <td>{{ $sk->tujuan ?? '-' }}</td>
                <td>{{ $sk->perihal ?? '-' }}</td>
                <td>{{ $sk->tanggal_surat ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; color: #777;">Tidak ada data surat keluar yang tersedia.</td>
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
