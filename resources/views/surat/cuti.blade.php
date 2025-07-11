<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Surat Cuti</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; line-height: 1.6; }
        .kop { text-align: center; margin-bottom: 20px; }
        .isi { margin: 0 40px; }
        .ttd { text-align: right; margin-top: 60px; margin-right: 60px; }
    </style>
</head>
<body>
    <div class="kop">
        <h3>PEMERINTAH PROVINSI ...</h3>
        <h4>SEKOLAH MENENGAH ATAS (SMA) ...</h4>
        <p>Alamat: Jl. Contoh No.123, Kota, Provinsi</p>
        <hr>
    </div>

    <div class="isi">
        <h4 style="text-align: center;"><u>SURAT PERMOHONAN CUTI</u></h4>
        <p style="text-align: center;">Nomor: 123/SMAN/2025</p>

        <p>Yang bertanda tangan di bawah ini:</p>
        <table>
            <tr><td>Nama</td><td>: {{ $cuti->pegawai->nama_lengkap }}</td></tr>
            <tr><td>NIP</td><td>: {{ $cuti->pegawai->nip }}</td></tr>
            <tr><td>Jabatan</td><td>: {{ $cuti->pegawai->jabatan }}</td></tr>
        </table>

        <p>Dengan ini mengajukan permohonan cuti:</p>
        <ul>
            <li><strong>Jenis Cuti:</strong> {{ $cuti->jenis_cuti }}</li>
            <li><strong>Mulai:</strong> {{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->translatedFormat('d F Y') }}</li>
            <li><strong>Selesai:</strong> {{ \Carbon\Carbon::parse($cuti->tanggal_selesai)->translatedFormat('d F Y') }}</li>
            <li><strong>Alasan:</strong> {{ $cuti->alasan ?? '-' }}</li>
        </ul>

        <p>Demikian permohonan ini saya sampaikan untuk dapat dipertimbangkan dan disetujui.</p>

        <div class="ttd">
            <p>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p>Hormat Saya,</p>
            <br><br><br>
            <p><u>{{ $cuti->pegawai->nama_lengkap }}</u></p>
        </div>
    </div>
</body>
</html>