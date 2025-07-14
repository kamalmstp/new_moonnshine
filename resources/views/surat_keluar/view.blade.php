<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Surat Keluar: {{ $suratKeluar->nomor_surat }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            color: #374151;
        }
        .detail-item {
            display: flex;
            flex-direction: column;
            padding: 8px 0;
        }
        .detail-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #6b7280;
            margin-bottom: 4px;
        }
        .detail-value {
            font-size: 1rem;
            font-weight: 600;
            color: #1f2937;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl p-8 max-w-3xl w-full">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center border-b pb-4">Detail Surat Keluar</h1>

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 mb-6">
            <div class="detail-item">
                <span class="detail-label">Nomor Surat:</span>
                <span class="detail-value">{{ $suratKeluar->nomor_surat }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Tujuan:</span>
                <span class="detail-value">{{ $suratKeluar->tujuan }}</span>
            </div>
            <div class="detail-item md:col-span-2">
                <span class="detail-label">Perihal:</span>
                <span class="detail-value">{{ $suratKeluar->perihal }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Tanggal Surat:</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($suratKeluar->tanggal_surat)->format('d F Y') }}</span>
            </div>
        </div>

        @if ($suratKeluar->file_surat)
            <div class="mt-8 pt-4 border-t text-center">
                <a href="{{ route('surat_keluar.download', ['id' => $suratKeluar->id]) }}"
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    <svg class="-ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v4.59L7.3 9.29a.75.75 0 00-1.06 1.06l3.25 3.25a.75.75 0 001.06 0l3.25-3.25a.75.75 0 10-1.06-1.06l-1.94 1.94V6.75z" clip-rule="evenodd" />
                    </svg>
                    Unduh File Surat
                </a>
                <p class="text-sm text-gray-500 mt-2">Nama File: {{ basename($suratKeluar->file_surat) }}</p>
            </div>
        @else
            <div class="mt-8 pt-4 border-t text-center text-gray-500">
                <p>Tidak ada file surat yang dilampirkan.</p>
            </div>
        @endif
    </div>
</body>
</html>