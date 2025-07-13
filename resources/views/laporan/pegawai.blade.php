@extends('moonshine::layout')
@section('title', 'Laporan Data Pegawai')

@section('content')
    <div class="p-4 bg-white rounded-xl shadow-md">
        <h2 class="text-2xl font-bold mb-4">Laporan Data Pegawai</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full table-auto border border-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-3 py-2 text-left">No</th>
                        <th class="border px-3 py-2 text-left">NIP</th>
                        <th class="border px-3 py-2 text-left">Nama</th>
                        <th class="border px-3 py-2 text-left">TTL</th>
                        <th class="border px-3 py-2 text-left">Jabatan</th>
                        <th class="border px-3 py-2 text-left">Pangkat/Golongan</th>
                        <th class="border px-3 py-2 text-left">Pendidikan</th>
                        <th class="border px-3 py-2 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pegawai as $index => $pgw)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-3 py-2 text-center">{{ $index + 1 }}</td>
                            <td class="border px-3 py-2">{{ $pgw->nip }}</td>
                            <td class="border px-3 py-2">{{ $pgw->nama_lengkap }}</td>
                            <td class="border px-3 py-2">
                                {{ $pgw->tempat_lahir }},
                                {{ optional($pgw->tanggal_lahir)->format('d-m-Y') }}
                            </td>
                            <td class="border px-3 py-2">{{ $pgw->jabatan }}</td>
                            <td class="border px-3 py-2">
                                {{ $pgw->pangkatGolongan?->nama_pangkat }} /
                                {{ $pgw->pangkatGolongan?->golongan }}
                            </td>
                            <td class="border px-3 py-2">{{ $pgw->pendidikan_terakhir }}</td>
                            <td class="border px-3 py-2">{{ $pgw->status_kepegawaian }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection