<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratKeluarViewController extends Controller
{
    public function show($id)
    {
        $suratKeluar = SuratKeluar::find($id);

        if (!$suratKeluar) {
            return redirect('/')->with('error', 'Surat keluar tidak ditemukan.');
        }

        return view('surat_keluar.view', compact('suratKeluar'));
    }

    public function downloadFile($id)
    {
        $suratKeluar = SuratKeluar::find($id);

        if (!$suratKeluar || !$suratKeluar->file_surat) {
            return redirect()->back()->with('error', 'File surat tidak ditemukan.');
        }

        $filePathInStorage = $suratKeluar->file_surat;

        if (!Storage::disk('public')->exists($filePathInStorage)) {
            return redirect()->back()->with('error', 'File tidak ada di server.');
        }

        $fileName = basename($suratKeluar->file_surat);

        return Storage::disk('public')->download($filePathInStorage, $fileName);
    }
}