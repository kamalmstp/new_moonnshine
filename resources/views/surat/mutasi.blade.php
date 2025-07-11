<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Surat Mutasi</title>
    <style>
        body { font-family: 'Times New Roman', serif; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 30px; }
        .content { margin: 20px; }
        .ttd { margin-top: 50px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>PEMERINTAH SMA NEGERI X</h2>
        <h3>SURAT MUTASI PEGAWAI</h3>
        <p>Nomor: {{ $mutasi->nomor_surat ?? '-' }}</p>
    </div>

    <div class="content">
        <p>Kepada Yth,</p>
        <p>{{ $mutasi->pegawai->nama_lengkap }}</p>

        <p>Dengan ini kami memberitahukan bahwa berdasarkan pertimbangan manajemen, Anda dimutasi dengan ketentuan sebagai berikut:</p>

        <ul>
            <li><strong>Jenis Mutasi:</strong> {{ $mutasi->jenis_mutasi }}</li>
            <li><strong>Tanggal Mutasi:</strong> {{ \Carbon\Carbon::parse($mutasi->tanggal_mutasi)->translatedFormat('d F Y') }}</li>
            <li><strong>Keterangan:</strong> {{ $mutasi->keterangan ?? '-' }}</li>
        </ul>

        <p>Demikian surat ini kami sampaikan untuk dapat dilaksanakan sebagaimana mestinya.</p>
    </div>

    <div class="ttd">
        <p>Mengetahui,</p>
        <p>Kepala Sekolah</p>
        <br><br>
        <p><strong>Nama Kepala Sekolah</strong></p>
    </div>
</body>
</html>