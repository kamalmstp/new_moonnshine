<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Perintah Perjalanan Dinas</title>
    <style>
        /* Menggunakan font yang lebih aman untuk Dompdf dan mendukung karakter Latin Extended */
        @font-face {
            font-family: 'ArialUnicodeMS';
            src: url('{{ public_path('fonts/arial-unicode-ms.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'Times New Roman', Times, serif; /* Menggunakan Times New Roman sesuai permintaan sebelumnya */
            line-height: 1.6;
            margin: 0;
            padding: 2cm; /* Margin untuk cetak */
            background-color: #f9f9f9;
            font-size: 12pt; /* Ukuran font standar untuk isi surat */
            color: #333;
        }
        .letter-container {
            width: 21cm; /* Ukuran A4 */
            min-height: 29.7cm; /* Ukuran A4 */
            margin: 0 auto;
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 2.5cm; /* Padding internal surat */
            box-sizing: border-box;
        }

        /* Header Laporan (disesuaikan dari template laporan) */
        .header {
            margin-bottom: 30px; /* Jarak dari bagian di bawahnya */
            border-bottom: 2px solid #000; /* Garis bawah hitam seperti di gambar */
            padding-bottom: 15px;
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

        /* Gaya konten surat */
        .content {
            margin-bottom: 30px;
            font-size: 12pt;
        }
        .content p {
            margin-bottom: 10px;
            text-align: justify;
        }
        .content .indent {
            text-indent: 0.5cm; /* Indentasi paragraf */
        }
        .content table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .content table td {
            padding: 5px 0;
            vertical-align: top;
        }
        .content table td:first-child {
            width: 30%; /* Lebar kolom label */
            padding-right: 10px;
        }
        .signature-block {
            margin-top: 50px;
            text-align: right;
            font-size: 12pt;
        }
        .signature-block .date {
            margin-bottom: 50px; /* Ruang untuk tanda tangan */
        }
        .signature-block .name {
            font-weight: bold;
            border-bottom: 1px solid #000;
            display: inline-block;
            padding-bottom: 2px;
            margin-top: 10px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10pt;
            border-top: 1px solid #eee;
            padding-top: 10px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="letter-container">
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

        <div class="content">
            <p style="text-align: right;">Banjarmasin, {{ \Carbon\Carbon::parse($perjalananDinas->tanggal_berangkat)->translatedFormat('d F Y') }}</p>
            <p>Nomor : [NOMOR SURAT TUGAS]</p>
            <p>Lampiran : -</p>
            <p>Hal : Surat Perintah Perjalanan Dinas</p>

            <br>

            <p>Kepada Yth.</p>
            <p>Pegawai yang bersangkutan</p>
            <p>di -</p>
            <p style="text-indent: 1cm;">Tempat</p>

            <br>

            <p class="indent">Yang bertanda tangan di bawah ini:</p>
            <table>
                <tr>
                    <td>Nama</td>
                    <td>: H. MUKENIANSYAH, S.Pd., M.I.Kom.</td>
                </tr>
                <tr>
                    <td>NIP</td>
                    <td>: 196507071997021002</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>: Plt. Kepala Sekolah</td>
                </tr>
            </table>

            <p class="indent">Dengan ini memerintahkan kepada:</p>

            <table>
                <tr>
                    <td>Nama</td>
                    <td>: {{ $perjalananDinas->pegawai->nama_lengkap ?? '-' }}</td>
                </tr>
                <tr>
                    <td>NIP</td>
                    <td>: {{ $perjalananDinas->pegawai->nip ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>: {{ $perjalananDinas->pegawai->jabatan ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Pangkat/Golongan</td>
                    <td>: {{ $perjalananDinas->pegawai->pangkatGolongan->pangkat_golongan ?? '-' }}</td>
                </tr>
            </table>

            <p class="indent">Untuk melaksanakan perjalanan dinas {{ $perjalananDinas->jenis_perjalanan ?? '-' }} dengan tujuan **{{ $perjalananDinas->tujuan ?? '-' }}**.</p>
            <p class="indent">Perjalanan dinas ini dilaksanakan terhitung mulai tanggal **{{ \Carbon\Carbon::parse($perjalananDinas->tanggal_berangkat)->translatedFormat('d F Y') }}** sampai dengan tanggal **{{ \Carbon\Carbon::parse($perjalananDinas->tanggal_kembali)->translatedFormat('d F Y') }}**.</p>
            <p class="indent">Keterangan tambahan: {{ $perjalananDinas->keterangan ?? '-' }}.</p>
            <p class="indent">Demikian surat perintah perjalanan dinas ini dibuat untuk dilaksanakan dengan penuh tanggung jawab.</p>
        </div>

        <div class="signature-block">
            <p>Dikeluarkan di : Banjarmasin</p>
            <p>Pada tanggal : {{ \Carbon\Carbon::parse($perjalananDinas->tanggal_berangkat)->translatedFormat('d F Y') }}</p>
            <br>
            <p>Plt. Kepala Sekolah,</p>
            <br><br><br> <!-- Jarak untuk tanda tangan manual -->
            <p class="name">H. MUKENIANSYAH, S.Pd., M.I.Kom.</p>
            <p class="title">Pembina Tk. I</p>
            <p class="nip">NIP 196507071997021002</p>
        </div>

    </div>
</body>
</html>
