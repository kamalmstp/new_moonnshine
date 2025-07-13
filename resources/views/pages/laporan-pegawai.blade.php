<h2 class="text-2xl font-bold mb-4">Laporan Daftar Pegawai</h2>
<button onclick="window.print()" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">
    Cetak
</button>
<table class="w-full table-auto border border-gray-200 text-sm">
    <thead class="bg-gray-100">
        <tr>
            <th class="border px-3 py-2 text-left">NIP</th>
            <th class="border px-3 py-2 text-left">Nama Lengkap</th>
            <th class="border px-3 py-2 text-left">Jabatan</th>
            <th class="border px-3 py-2 text-left">Status Kepegawaian</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pegawai as $item)
            <tr>
                <td class="border px-3 py-2">{{ $item->nip }}</td>
                <td class="border px-3 py-2">{{ $item->nama_lengkap }}</td>
                <td class="border px-3 py-2">{{ $item->jabatan }}</td>
                <td class="border px-3 py-2">{{ $item->status_kepegawaian }}</td>
            </tr>
        @endforeach
    </tbody>
</table>