<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratMasukViewController extends Controller
{

    public function show($id)
    {
        $suratMasuk = SuratMasuk::find($id);

        if (!$suratMasuk) {
            return redirect('/')->with('error', 'Surat masuk tidak ditemukan.');
        }

        return view('surat_masuk.view', compact('suratMasuk'));
    }

    public function downloadFile($id)
    {
        $suratMasuk = SuratMasuk::find($id);

        if (!$suratMasuk || !$suratMasuk->file_surat) {
            return redirect()->back()->with('error', 'File surat tidak ditemukan.');
        }

        $filePathInStorage = $suratMasuk->file_surat;

        if (!Storage::disk('public')->exists($filePathInStorage)) {
            return redirect()->back()->with('error', 'File tidak ada di server atau path salah.');
        }

        $fileName = basename($suratMasuk->file_surat);

        return Storage::disk('public')->download($filePathInStorage, $fileName);
    }
}
