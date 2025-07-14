<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Surat Masuk: {{ $suratMasuk->nomor_surat }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6; /* Warna latar belakang umum Moonshine */
            color: #374151;
        }
        /* Penyesuaian untuk tampilan detail info */
        .detail-item {
            display: flex;
            flex-direction: column;
            padding: 8px 0; /* Padding vertikal untuk setiap item */
        }
        .detail-label {
            font-size: 0.875rem; /* text-sm */
            font-weight: 500; /* font-medium */
            color: #6b7280; /* text-gray-500 */
            margin-bottom: 4px; /* Sedikit jarak antara label dan nilai */
        }
        .detail-value {
            font-size: 1rem; /* text-base */
            font-weight: 600; /* font-semibold */
            color: #1f2937; /* text-gray-900 */
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl p-8 max-w-3xl w-full"> <!-- Menggunakan rounded-xl dan max-w-3xl -->
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center border-b pb-4">Detail Surat Masuk</h1> <!-- Border bawah untuk judul -->

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 mb-6"> <!-- Menambah gap-x dan gap-y -->
            <div class="detail-item">
                <span class="detail-label">Nomor Surat:</span>
                <span class="detail-value">{{ $suratMasuk->nomor_surat }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Pengirim:</span>
                <span class="detail-value">{{ $suratMasuk->pengirim }}</span>
            </div>
            <div class="detail-item md:col-span-2"> <!-- Perihal mengambil 2 kolom -->
                <span class="detail-label">Perihal:</span>
                <span class="detail-value">{{ $suratMasuk->perihal }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Tanggal Surat:</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($suratMasuk->tanggal_surat)->format('d F Y') }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Tanggal Diterima:</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($suratMasuk->tanggal_diterima)->format('d F Y') }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Status:</span>
                <span class="detail-value">{{ $suratMasuk->status }}</span>
            </div>
        </div>

        @if ($suratMasuk->file_surat)
            <div class="mt-8 pt-4 border-t text-center"> <!-- Border atas untuk bagian unduh -->
                <a href="{{ route('surat_masuk.download', ['id' => $suratMasuk->id]) }}"
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out"> <!-- Menggunakan warna biru Moonshine -->
                    <svg class="-ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v4.59L7.3 9.29a.75.75 0 00-1.06 1.06l3.25 3.25a.75.75 0 001.06 0l3.25-3.25a.75.75 0 10-1.06-1.06l-1.94 1.94V6.75z" clip-rule="evenodd" />
                    </svg>
                    Unduh File Surat
                </a>
                <p class="text-sm text-gray-500 mt-2">Nama File: {{ basename($suratMasuk->file_surat) }}</p>
            </div>
        @else
            <div class="mt-8 pt-4 border-t text-center text-gray-500">
                <p>Tidak ada file surat yang dilampirkan.</p>
            </div>
        @endif
    </div>
</body>
</html>
