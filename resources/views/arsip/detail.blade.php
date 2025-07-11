<x-moonshine::layout title="Detail Arsip Dokumen">
    <x-moonshine::card title="{{ $arsip->nama_dokumen }}">
        <p><strong>Jenis:</strong> {{ $arsip->jenis_dokumen }}</p>
        <p><strong>Nomor Surat:</strong> {{ $arsip->nomor_surat }}</p>
        <p><strong>Tanggal Surat:</strong> {{ $arsip->tanggal_surat->format('d-m-Y') }}</p>
        <p><strong>Perihal:</strong> {{ $arsip->perihal }}</p>

        @if($arsip->pegawai)
            <p><strong>Pegawai:</strong> {{ $arsip->pegawai->nama_lengkap }}</p>
        @endif

        <p><strong>File:</strong> 
            <a href="{{ asset('storage/' . $arsip->file_path) }}" target="_blank" class="btn btn-primary">Lihat File</a>
        </p>

        <p><strong>QR Code:</strong></p>
        @if($arsip->qr_code)
            <img src="{{ asset('storage/' . $arsip->qr_code) }}" alt="QR Code">
        @endif
    </x-moonshine::card>
</x-moonshine::layout>